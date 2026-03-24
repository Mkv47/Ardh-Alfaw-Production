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
        $tmpDir = storage_path('app/tmp/backup_' . uniqid());
        @mkdir($tmpDir, 0755, true);
        @mkdir($tmpDir . '/storage', 0755, true);

        try {
            // 1. Dump all tables to data.json
            $data = [];
            foreach ($this->tables as $table) {
                try {
                    $data[$table] = DB::table($table)->get()->toArray();
                } catch (\Exception) {
                    $data[$table] = [];
                }
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

            // 3. Copy storage files into tmp dir
            $srcStorage = storage_path('app/public');
            if (is_dir($srcStorage)) {
                $this->copyDirectory($srcStorage, $tmpDir . '/storage');
            }

            // 4. Build tar.gz using PharData
            $tarPath = storage_path('app/tmp/ardhfaw_backup_' . now()->format('Y-m-d_His') . '.tar');
            if (file_exists($tarPath))      unlink($tarPath);
            if (file_exists($tarPath . '.gz')) unlink($tarPath . '.gz');

            $phar = new \PharData($tarPath);
            $phar->buildFromDirectory($tmpDir);
            $phar->compress(\Phar::GZ);     // creates .tar.gz
            unlink($tarPath);               // remove uncompressed tar
            $this->deleteDirectory($tmpDir);

            $gzPath    = $tarPath . '.gz';
            $filename  = 'ardhfaw_backup_' . now()->format('Y-m-d_His') . '.tar.gz';

            return response()->download($gzPath, $filename)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            $this->deleteDirectory($tmpDir);
            return back()->with('error', 'فشل التصدير: ' . $e->getMessage());
        }
    }

    // ── Import ──────────────────────────────────────────────────────────────

    public function import(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|max:512000',
        ]);

        $file     = $request->file('backup_file');
        $origName = $file->getClientOriginalName();

        if (!str_ends_with($origName, '.tar.gz') && !str_ends_with($origName, '.tgz')) {
            return back()->with('error', 'يُقبل فقط ملفات .tar.gz الصادرة من هذا النظام');
        }

        $tmpDir = storage_path('app/tmp/restore_' . uniqid());
        @mkdir($tmpDir, 0755, true);

        // Move uploaded file with .tar.gz extension so PharData recognises it
        $tarGzPath = $tmpDir . '/backup.tar.gz';
        $file->move($tmpDir, 'backup.tar.gz');

        try {
            $phar = new \PharData($tarGzPath);
            $phar->extractTo($tmpDir . '/extracted', null, true);

            $extractedDir = $tmpDir . '/extracted';

            // 1. Restore DB
            $jsonFile = $extractedDir . '/data.json';
            if (!file_exists($jsonFile)) {
                throw new \Exception('الملف لا يحتوي على data.json — تأكد أن الملف صادر من نفس النظام');
            }

            $data = json_decode(file_get_contents($jsonFile), true);
            if (!$data) throw new \Exception('تعذّر قراءة بيانات قاعدة البيانات');

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

            // 2. Restore storage files
            $storageSrc = $extractedDir . '/storage';
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
