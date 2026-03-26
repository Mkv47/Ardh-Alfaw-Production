<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BackupController extends Controller
{
    private array $tables = [
        'users', 'settings', 'services', 'projects', 'news',
        'team_members', 'tenders', 'clients', 'gallery_items',
        'certificates', 'contact_messages',
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
            $data = [];
            foreach ($this->tables as $table) {
                try { $data[$table] = DB::table($table)->get()->toArray(); }
                catch (\Exception) { $data[$table] = []; }
            }
            file_put_contents($tmpDir . '/data.json',
                json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            file_put_contents($tmpDir . '/meta.json', json_encode([
                'version' => '1.0', 'created_at' => now()->toISOString(),
                'app'     => config('app.name'),
            ], JSON_PRETTY_PRINT));

            if (file_exists($tarPath)) unlink($tarPath);
            if (file_exists($gzPath))  unlink($gzPath);

            $phar = new \PharData($tarPath);
            $phar->buildFromDirectory($tmpDir);

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

            $phar->compress(\Phar::GZ);
            unlink($tarPath);
            $this->deleteDirectory($tmpDir);

            $filename = 'ardhfaw_backup_' . now()->format('Y-m-d_His') . '.tar.gz';
            $size     = filesize($gzPath);

            return response()->stream(function () use ($gzPath) {
                $handle = fopen($gzPath, 'rb');
                while (!feof($handle)) {
                    echo fread($handle, 65536);
                    ob_flush(); flush();
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
            @unlink($tarPath); @unlink($gzPath);
            return back()->with('error', 'فشل التصدير: ' . $e->getMessage());
        }
    }

    // ── Import: receive one chunk ────────────────────────────────────────────

    public function importChunk(Request $request)
    {
        $uploadId   = $request->input('upload_id');
        $chunkIndex = (int) $request->input('chunk_index');
        $totalChunks= (int) $request->input('total_chunks');

        if (!$uploadId || !$request->hasFile('chunk')) {
            return response()->json(['error' => 'بيانات غير صحيحة'], 400);
        }

        $chunkDir = storage_path('app/tmp/chunks_' . $uploadId);
        @mkdir($chunkDir, 0755, true);

        $request->file('chunk')->move($chunkDir, 'chunk_' . str_pad($chunkIndex, 5, '0', STR_PAD_LEFT));

        $received = count(glob($chunkDir . '/chunk_*'));

        return response()->json([
            'received'     => $received,
            'total_chunks' => $totalChunks,
            'done'         => $received >= $totalChunks,
        ]);
    }

    // ── Import: assemble chunks and restore ──────────────────────────────────

    public function importFinalize(Request $request)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $uploadId    = $request->input('upload_id');
        $totalChunks = (int) $request->input('total_chunks');
        $chunkDir    = storage_path('app/tmp/chunks_' . $uploadId);

        if (!is_dir($chunkDir)) {
            return response()->json(['error' => 'جلسة الرفع غير موجودة'], 400);
        }

        $tarGzPath = storage_path('app/tmp/restore_' . $uploadId . '.tar.gz');
        $tmpDir    = storage_path('app/tmp/restore_ext_' . $uploadId);
        @mkdir($tmpDir, 0755, true);

        try {
            // Assemble chunks into one file
            $out = fopen($tarGzPath, 'wb');
            for ($i = 0; $i < $totalChunks; $i++) {
                $chunkFile = $chunkDir . '/chunk_' . str_pad($i, 5, '0', STR_PAD_LEFT);
                if (!file_exists($chunkFile)) {
                    throw new \Exception("القطعة {$i} مفقودة");
                }
                $in = fopen($chunkFile, 'rb');
                while (!feof($in)) fwrite($out, fread($in, 65536));
                fclose($in);
            }
            fclose($out);
            $this->deleteDirectory($chunkDir);

            // Extract
            $phar = new \PharData($tarGzPath);
            $phar->extractTo($tmpDir, null, true);

            $jsonFile = $tmpDir . '/data.json';
            if (!file_exists($jsonFile)) {
                throw new \Exception('الملف لا يحتوي على data.json');
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

            $storageSrc = $tmpDir . '/storage';
            if (is_dir($storageSrc)) {
                $this->copyDirectory($storageSrc, storage_path('app/public'));
            }

            $this->deleteDirectory($tmpDir);
            @unlink($tarGzPath);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            $this->deleteDirectory($chunkDir);
            $this->deleteDirectory($tmpDir);
            @unlink($tarGzPath);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ── Legacy single-request import (kept for compatibility) ────────────────

    public function import(Request $request)
    {
        return back()->with('error', 'استخدم واجهة الرفع الجديدة');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

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
