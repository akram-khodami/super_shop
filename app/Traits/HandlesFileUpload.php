<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandlesFileUpload
{
    protected function uploadFile(?UploadedFile $file, string $directory, ?string $oldPath = null): ?string
    {
        if (!$file) {
            return null;
        }

        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        return $file->store($directory, 'public');
    }

    protected function deleteFile(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
