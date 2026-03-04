<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Support\Facades\Storage;

trait SavesCroppedImage
{
    protected function saveCroppedImage(string $base64, string $folder): string
    {
        $ext = 'jpg';
        if (preg_match('/^data:image\/(\w+);base64,/', $base64, $m)) {
            $ext    = $m[1] === 'jpeg' ? 'jpg' : $m[1];
            $base64 = substr($base64, strpos($base64, ',') + 1);
        } else {
            $base64 = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        }
        $decoded = base64_decode($base64);
        $path    = $folder . '/' . uniqid() . '.' . $ext;
        Storage::disk('public')->put($path, $decoded);
        return $path;
    }
}
