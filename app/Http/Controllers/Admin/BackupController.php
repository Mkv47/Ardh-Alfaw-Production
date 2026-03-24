<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class BackupController extends Controller
{
    // Tables to include in backup (in restore order to respect FK dependencies)
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
        $tmpZip = tempnam(sys_get_temp_dir(), 'ardhfaw_backup_') . '.zip';

        $zip = new ZipArchive();
        if ($zip->open($tmpZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'تعذّر إنشاء ملف النسخة الاحتياطية');
        }

        // 1. Database tables as JSON
        $data = [];
        foreach ($this->tables as $table) {
            try {
                $data[$table] = DB::table($table)->get()->toArray();
            } catch (\Exception $e) {
                $data[$table] = [];
            }
        }
        $zip->addFromString('data.json', json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        // 2. Meta info
        $meta = [
            'version'    => '1.0',
            'created_at' => now()->toISOString(),
            'app'        => config('app.name'),
        ];
        $zip->addFromString('meta.json', json_encode($meta, JSON_PRETTY_PRINT));

        // 3. Storage files (storage/app/public/)
        $storagePath = storage_path('app/public');
        if (is_dir($storagePath)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($storagePath, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );
            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $relativePath = 'storage/' . str_replace('\\', '/', substr($file->getRealPath(), strlen($storagePath) + 1));
                    $zip->addFile($file->getRealPath(), $relativePath);
                }
            }
        }

        $zip->close();

        $filename = 'ardhfaw_backup_' . now()->format('Y-m-d_His') . '.zip';

        return response()->download($tmpZip, $filename)->deleteFileAfterSend(true);
    }

    // ── Import ──────────────────────────────────────────────────────────────

    public function import(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:zip|max:512000',
        ]);

        $uploadedZip = $request->file('backup_file');
        $tmpZip = $uploadedZip->getRealPath();

        $zip = new ZipArchive();
        if ($zip->open($tmpZip) !== true) {
            return back()->with('error', 'الملف المرفوع ليس ملف ZIP صالحاً');
        }

        // Validate it's our backup format
        if ($zip->locateName('data.json') === false) {
            $zip->close();
            return back()->with('error', 'ملف النسخة الاحتياطية غير صالح — لا يحتوي على data.json');
        }

        $tmpDir = sys_get_temp_dir() . '/ardhfaw_restore_' . uniqid();
        $zip->extractTo($tmpDir);
        $zip->close();

        try {
            // 1. Restore database
            $json = file_get_contents($tmpDir . '/data.json');
            $data = json_decode($json, true);

            if (!$data) {
                throw new \Exception('تعذّر قراءة بيانات قاعدة البيانات');
            }

            DB::transaction(function () use ($data) {
                // Disable FK checks for clean restore
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');

                foreach (array_reverse($this->tables) as $table) {
                    if (isset($data[$table])) {
                        try {
                            DB::table($table)->truncate();
                        } catch (\Exception) {}
                    }
                }

                foreach ($this->tables as $table) {
                    if (!empty($data[$table])) {
                        $rows = array_map(fn($r) => (array) $r, $data[$table]);
                        // Insert in chunks to avoid query size limits
                        foreach (array_chunk($rows, 100) as $chunk) {
                            DB::table($table)->insert($chunk);
                        }
                    }
                }

                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            });

            // 2. Restore storage files
            $storageSrc = $tmpDir . '/storage';
            if (is_dir($storageSrc)) {
                $storageDest = storage_path('app/public');
                $this->copyDirectory($storageSrc, $storageDest);
            }

            // 3. Clean up
            $this->deleteDirectory($tmpDir);

        } catch (\Exception $e) {
            $this->deleteDirectory($tmpDir);
            return back()->with('error', 'فشل الاستيراد: ' . $e->getMessage());
        }

        return back()->with('success', 'تم استيراد النسخة الاحتياطية بنجاح. قد تحتاج إلى تسجيل الدخول مجدداً.');
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    private function copyDirectory(string $src, string $dst): void
    {
        if (!is_dir($dst)) {
            mkdir($dst, 0755, true);
        }
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($src, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($iterator as $item) {
            $target = $dst . '/' . $iterator->getSubPathname();
            if ($item->isDir()) {
                if (!is_dir($target)) mkdir($target, 0755, true);
            } else {
                copy($item->getRealPath(), $target);
            }
        }
    }

    private function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) return;
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($iterator as $item) {
            $item->isDir() ? rmdir($item->getRealPath()) : unlink($item->getRealPath());
        }
        rmdir($dir);
    }
}
