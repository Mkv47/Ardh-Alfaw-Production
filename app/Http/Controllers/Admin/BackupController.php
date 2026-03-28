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
        $uploadId    = $request->input('upload_id');
        $chunkIndex  = (int) $request->input('chunk_index');
        $totalChunks = (int) $request->input('total_chunks');

        if (!$uploadId || !$request->hasFile('chunk')) {
            return response()->json(['error' => 'بيانات غير صحيحة'], 400);
        }

        $baseDir   = storage_path('app/public/restore_temp');
        $tarGzPath = $baseDir . '/restore_' . $uploadId . '.tar.gz';

        if (!is_dir($baseDir) && !mkdir($baseDir, 0755, true) && !is_dir($baseDir)) {
            return response()->json(['error' => 'فشل إنشاء المجلد: ' . $baseDir], 500);
        }

        // Chunk 0 always starts a fresh file (clears any previous partial upload)
        if ($chunkIndex === 0 && file_exists($tarGzPath)) {
            unlink($tarGzPath);
        }

        try {
            $chunkPath = $request->file('chunk')->getRealPath();
            if (!$chunkPath || !file_exists($chunkPath)) {
                return response()->json(['error' => 'ملف القطعة المؤقت غير موجود'], 500);
            }

            $written = file_put_contents($tarGzPath, file_get_contents($chunkPath), FILE_APPEND | LOCK_EX);
            if ($written === false) {
                return response()->json([
                    'error' => 'فشل كتابة القطعة إلى: ' . $tarGzPath,
                    'writable' => is_writable($baseDir),
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'خطأ: ' . $e->getMessage()], 500);
        }

        $fileSize = file_exists($tarGzPath) ? filesize($tarGzPath) : 0;

        return response()->json([
            'received'     => $chunkIndex + 1,
            'total_chunks' => $totalChunks,
            'done'         => ($chunkIndex + 1) >= $totalChunks,
            'file_size'    => $fileSize,
        ]);
    }

    // ── Import: finalize in 2 steps (extract → restore) ──────────────────────
    // Chunks are already appended directly to the tar.gz during upload,
    // so no assembly step is needed. Each step is a separate HTTP call
    // to avoid Hostinger's request timeout.

    public function importFinalize(Request $request)
    {
        set_time_limit(300);
        ini_set('memory_limit', '-1');

        $uploadId  = $request->input('upload_id');
        $step      = (int) $request->input('step', 1);
        $tarGzPath = storage_path('app/public/restore_temp/restore_' . $uploadId . '.tar.gz');
        $tmpDir    = storage_path('app/public/restore_temp/ext_' . $uploadId);

        try {
            // ── Step 1: Extract tar.gz ────────────────────────────────────────
            if ($step === 1) {
                $restoreDir = storage_path('app/public/restore_temp');
                if (!file_exists($tarGzPath)) {
                    return response()->json([
                        'error'        => 'ملف الأرشيف غير موجود، أعد الرفع من البداية',
                        'looking_for'  => $tarGzPath,
                        'dir_exists'   => is_dir($restoreDir),
                        'files_in_dir' => is_dir($restoreDir)
                            ? array_values(array_diff(scandir($restoreDir), ['.', '..']))
                            : [],
                    ], 400);
                }

                if (!is_dir($tmpDir) && !mkdir($tmpDir, 0755, true) && !is_dir($tmpDir)) {
                    return response()->json(['error' => 'فشل إنشاء مجلد الاستخراج'], 500);
                }

                // Try fast system tar first, fall back to PharData
                $tarGzEscaped = escapeshellarg($tarGzPath);
                $tmpDirEscaped = escapeshellarg($tmpDir);
                $output = []; $code = 0;
                if (function_exists('exec')) {
                    exec("tar -xzf {$tarGzEscaped} -C {$tmpDirEscaped} 2>&1", $output, $code);
                }

                if (!function_exists('exec') || $code !== 0) {
                    // Fallback to PharData
                    $phar = new \PharData($tarGzPath);
                    $phar->extractTo($tmpDir, null, true);
                }

                return response()->json(['success' => true, 'step' => 1]);
            }

            // ── Step 2: Restore DB + storage ─────────────────────────────────
            if ($step === 2) {
                if (!is_dir($tmpDir)) {
                    return response()->json(['error' => 'مجلد الاستخراج غير موجود، أعد الرفع من البداية'], 400);
                }

                $jsonFile = $tmpDir . '/data.json';
                if (!file_exists($jsonFile)) {
                    throw new \Exception('الملف لا يحتوي على data.json');
                }

                $data = json_decode(file_get_contents($jsonFile), true);
                if (!$data) throw new \Exception('تعذّر قراءة بيانات قاعدة البيانات');

                // TRUNCATE causes implicit commit in MySQL so it must be outside any transaction
                try { DB::statement('SET FOREIGN_KEY_CHECKS=0;'); } catch (\Exception) {}
                foreach (array_reverse($this->tables) as $table) {
                    if (isset($data[$table])) {
                        try { DB::table($table)->truncate(); } catch (\Exception) {}
                    }
                }

                // Inserts are safe inside a transaction
                DB::transaction(function () use ($data) {
                    foreach ($this->tables as $table) {
                        if (!empty($data[$table])) {
                            $rows = array_map(fn($r) => (array) $r, $data[$table]);
                            foreach (array_chunk($rows, 100) as $chunk) {
                                DB::table($table)->insert($chunk);
                            }
                        }
                    }
                });

                try { DB::statement('SET FOREIGN_KEY_CHECKS=1;'); } catch (\Exception) {};

                $storageSrc = $tmpDir . '/storage';
                if (is_dir($storageSrc)) {
                    $this->copyDirectory($storageSrc, storage_path('app/public'));
                }

                $this->deleteDirectory($tmpDir);
                @unlink($tarGzPath);

                return response()->json(['success' => true, 'step' => 2]);
            }

            return response()->json(['error' => 'خطوة غير صالحة'], 400);

        } catch (\Exception $e) {
            if ($step === 2) {
                $this->deleteDirectory($tmpDir);
                @unlink($tarGzPath);
            }
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
