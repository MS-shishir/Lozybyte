<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;

trait UploadsImage
{
    /**
     * Upload and optimize an image.
     *
     * @param UploadedFile $file
     * @param string $path
     * @param int|null $width
     * @param int|null $height
     * @return string
     */
    public function uploadImage(UploadedFile $file, $path = 'uploads', $width = null, $height = null)
    {
        $manager = new ImageManager(new Driver());
        
        $image = $manager->decode($file->getRealPath());

        if ($width || $height) {
            $image->scaleDown(width: $width, height: $height);
        }

        // Convert to WebP for optimization
        $encoded = $image->encode(new WebpEncoder(80));

        $filename = uniqid() . '_' . time() . '.webp';
        $fullPath = $path . '/' . $filename;

        Storage::disk('public')->put($fullPath, (string) $encoded);

        return Storage::disk('public')->url($fullPath);
    }

    /**
     * Upload image and return relative storage path (not full URL).
     * Use this for storing images that need to be served via API with proper URL generation.
     */
    public function uploadImagePath(UploadedFile $file, $path = 'uploads', $width = null, $height = null): string
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->decode($file->getRealPath());

        if ($width || $height) {
            $image->scaleDown(width: $width, height: $height);
        }

        $encoded = $image->encode(new WebpEncoder(80));
        $filename = uniqid() . '_' . time() . '.webp';
        $fullPath = $path . '/' . $filename;

        Storage::disk('public')->put($fullPath, (string) $encoded);

        return $fullPath; // returns e.g. "products/abc123_1234567890.webp"
    }

    public function deleteImage($path)
    {
        if ($path) {
            $path = ltrim($path, '/');
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }
}
