<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Support\Facades\Storage;

trait SavesCroppedImage
{
    protected function saveCroppedImage(string $base64, string $folder): string
    {
        $base64  = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $decoded = base64_decode($base64);
        $path    = $folder . '/' . uniqid() . '.jpg';
        Storage::disk('public')->put($path, $decoded);
        return $path;
    }
}
