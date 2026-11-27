<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait HandlesMenuImage
{
    /**
     * Upload foto menu ke disk S3 (Supabase Storage) dan hapus file lama bila ada.
     */
    protected function handleMenuImageUpload(Request $request, ?string $oldImageUrl = null): ?string
    {
        if (! $request->hasFile('image')) {
            return $oldImageUrl;
        }

        if ($oldImageUrl) {
            $this->deleteMenuImageFromS3($oldImageUrl);
        }

        $path = $request->file('image')->store('menus', 's3');

        return Storage::disk('s3')->url($path);
    }

    /**
     * Hapus foto menu dari disk S3 berdasarkan URL atau path relatif.
     */
    protected function deleteMenuImageFromS3(?string $imageUrl): void
    {
        if (! $imageUrl) {
            return;
        }

        $parsedPath = parse_url($imageUrl, PHP_URL_PATH);
        $relativePath = $imageUrl;

        if (is_string($parsedPath) && str_contains($parsedPath, '/menus/')) {
            $relativePath = 'menus/' . ltrim(explode('/menus/', $parsedPath, 2)[1], '/');
        }

        try {
            Storage::disk('s3')->delete($relativePath);
        } catch (\Throwable) {
            // Abaikan error penyimpanan eksternal agar operasi database tetap konsisten.
        }
    }
}
