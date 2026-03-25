<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BackupController extends Controller
{
    private array $tables = [
        'users',
        'settings',
        'services',
        'projects',
        'news',
        'team_members',
        'tenders',
        'clients',
        'gallery_items',
        'certificates',
        'contact_messages',
    ];

    public function index()
    {
        return view('admin.backup.index');
    }

    // ── Export ──────────────────────────────────────────────────────────────

    public function export()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $tmpDir  = storage_path('app/tmp/backup_' . uniqid());
        $tarPath = storage_path('app/tmp/ardhfaw_backup_' . now()->format('Y-m-d_His') . '.tar');
        $gzPath  = $tarPath . '.gz';

        @mkdir($tmpDir, 0755, true);

        try {
            // 1. Dump all tables → data.json
            $data = [];
            foreach ($this->tables as $table) {
                try { $data[$table] = DB::table($table)->get()->toArray(); }
                catch (\Exception) { $data[$table] = []; }
            }
            file_put_contents(
                $tmpDir . '/data.json',
                json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
            );

            // 2. Meta
            file_put_contents($tmpDir . '/meta.json', json_encode([
                'version'    => '1.0',
                'created_at' => now()->toISOString(),
                'app'        => config('app.name'),
            ], JSON_PRETTY_PRINT));

            // 3. Build tar directly from storage (no intermediate copy)
            if (file_exists($tarPath))  unlink($tarPath);
            if (file_exists($gzPath))   unlink($gzPath);

            $phar = new \PharData($tarPath);

            // Add JSON files
            $phar->buildFromDirectory($tmpDir);

            // Add storage files under a 'storage/' prefix
            $srcStorage = storage_path('app/public');
            if (is_dir($srcStorage)) {
                $it = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($srcStorage, \RecursiveDirectoryIterator::SKIP_DOTS)
                );
                foreach ($it as $file) {
                    if ($file->isFile()) {
                        $relative = 'storage/' . ltrim(str_replace($srcStorage, '', $file->getRealPath()), '/\\');
                        $phar->addFile($file->getRealPath(), $relative);
                    }
                }
            }

            // 4. Compress → .tar.gz
            $phar->compress(\Phar::GZ);
            unlink($tarPath);
            $this->deleteDirectory($tmpDir);

            $filename = 'ardhfaw_backup_' . now()->format('Y-m-d_His') . '.tar.gz';
            $size     = filesize($gzPath);

            // 5. Stream in chunks so the connection never times out mid-download
            return response()->stream(function () use ($gzPath) {
                $handle = fopen($gzPath, 'rb');
                while (!feof($handle)) {
                    echo fread($handle, 1024 * 64); // 64 KB chunks
                    ob_flush();
                    flush();
                }
                fclose($handle);
                @unlink($gzPath);
            }, 200, [
                'Content-Type'              => 'application/gzip',
                'Content-Disposition'       => 'attachment; filename="' . $filename . '"',
                'Content-Length'            => $size,
                'Content-Transfer-Encoding' => 'binary',
                'Cache-Control'             => 'no-cache, no-store',
            ]);

        } catch (\Exception $e) {
            $this->deleteDirectory($tmpDir);
            if (file_exists($tarPath)) @unlink($tarPath);
            if (file_exists($gzPath))  @unlink($gzPath);
            return back()->with('error', 'فشل التصدير: ' . $e->getMessage());
        }
    }

    // ── Import ──────────────────────────────────────────────────────────────

    public function import(Request $request)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $request->validate([
            'backup_file' => 'required|file|max:614400', // 600 MB
        ]);

        $file     = $request->file('backup_file');
        $origName = $file->getClientOriginalName();

        if (!str_ends_with($origName, '.tar.gz') && !str_ends_with($origName, '.tgz')) {
            return back()->with('error', 'يُقبل فقط ملفات .tar.gz الصادرة من هذا النظام');
        }

        $tmpDir = storage_path('app/tmp/restore_' . uniqid());
        @mkdir($tmpDir, 0755, true);
        $file->move($tmpDir, 'backup.tar.gz');

        try {
            $phar = new \PharData($tmpDir . '/backup.tar.gz');
            $phar->extractTo($tmpDir . '/extracted', null, true);

            $extracted = $tmpDir . '/extracted';
            $jsonFile  = $extracted . '/data.json';

            if (!file_exists($jsonFile)) {
                throw new \Exception('الملف لا يحتوي على data.json');
            }

            $data = json_decode(file_get_contents($jsonFile), true);
            if (!$data) throw new \Exception('تعذّر قراءة بيانات قاعدة البيانات');

            // Restore DB
            DB::transaction(function () use ($data) {
                try { DB::statement('SET FOREIGN_KEY_CHECKS=0;'); } catch (\Exception) {}

                foreach (array_reverse($this->tables) as $table) {
                    if (isset($data[$table])) {
                        try { DB::table($table)->truncate(); } catch (\Exception) {}
                    }
                }
                foreach ($this->tables as $table) {
                    if (!empty($data[$table])) {
                        $rows = array_map(fn($r) => (array) $r, $data[$table]);
                        foreach (array_chunk($rows, 100) as $chunk) {
                            DB::table($table)->insert($chunk);
                        }
                    }
                }

                try { DB::statement('SET FOREIGN_KEY_CHECKS=1;'); } catch (\Exception) {}
            });

            // Restore storage files
            $storageSrc = $extracted . '/storage';
            if (is_dir($storageSrc)) {
                $this->copyDirectory($storageSrc, storage_path('app/public'));
            }

            $this->deleteDirectory($tmpDir);

        } catch (\Exception $e) {
            $this->deleteDirectory($tmpDir);
            return back()->with('error', 'فشل الاستيراد: ' . $e->getMessage());
        }

        return back()->with('success', 'تم استيراد النسخة الاحتياطية بنجاح.');
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    private function copyDirectory(string $src, string $dst): void
    {
        if (!is_dir($dst)) mkdir($dst, 0755, true);
        $it = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($src, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($it as $item) {
            $target = $dst . DIRECTORY_SEPARATOR . $it->getSubPathname();
            $item->isDir() ? (@mkdir($target, 0755, true)) : copy($item->getRealPath(), $target);
        }
    }

    private function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) return;
        $it = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($it as $item) {
            $item->isDir() ? rmdir($item->getRealPath()) : unlink($item->getRealPath());
        }
        rmdir($dir);
    }
}
