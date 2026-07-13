<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Media;
use Intervention\Image\Facades\Image;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::query();

        if ($request->filled('search')) {
            $query->where('filename', 'like', '%' . $request->input('search') . '%');
        }

        $mediaFiles = $query->latest()->paginate(24);
        return view('admin.media.index', compact('mediaFiles'));
    }

    public function store(Request $request)
    {
        \Illuminate\Support\Facades\Cache::flush();
        $request->validate([
            'file' => 'required|file|image|max:10240' // max 10MB
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Optimize image using Intervention Image
            $image = Image::read($file->getRealPath());

            // Auto-orient based on EXIF
            if (method_exists($image, 'orientate')) {
                $image->orientate();
            }

            // Constrain to max 1920px
            $maxDim = 1920;
            if ($image->width() > $maxDim || $image->height() > $maxDim) {
                $image->resize($maxDim, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            // Generate unique filename
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $filename);
            $filename = $filename . '_' . uniqid() . '.webp';

            // Store optimized WebP
            $relativePath = 'uploads/' . $filename;
            $image->toWebp(80)->save(storage_path('app/public/' . $relativePath));

            // Generate thumbnail
            $thumbPath = 'uploads/thumb_' . $filename;
            $image->fit(400, 400, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->toWebp(75)->save(storage_path('app/public/' . $thumbPath));

            $fullUrl = Storage::disk('public')->url($relativePath);

            $media = Media::create([
                'filename' => $file->getClientOriginalName(),
                'file_path' => $fullUrl,
                'file_type' => 'image/webp',
                'file_size' => filesize(storage_path('app/public/' . $relativePath)),
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'url' => $fullUrl,
                    'thumb' => Storage::disk('public')->url($thumbPath),
                    'media' => $media
                ]);
            }

            return redirect()->route('admin.media.index')->with('success', 'File uploaded and optimized successfully.');
        }

        return redirect()->route('admin.media.index')->with('error', 'File upload failed.');
    }

    public function destroy(Media $media)
    {
        \Illuminate\Support\Facades\Cache::flush();
        // Delete physical file from public disk
        $filePath = $media->file_path;
        $relativePath = $filePath;
        if (preg_match('/^https?:\/\/[^\/]+\/storage\/(.+)$/i', $filePath, $matches)) {
            $relativePath = $matches[1];
        } elseif (str_starts_with(ltrim($filePath, '/'), 'storage/')) {
            $relativePath = str_replace('storage/', '', ltrim($filePath, '/'));
        } else {
            $relativePath = ltrim($filePath, '/');
        }
        
        Storage::disk('public')->delete($relativePath);

        // Also delete thumbnail if exists
        $filename = basename($relativePath);
        $thumbPath = 'uploads/thumb_' . $filename;
        Storage::disk('public')->delete($thumbPath);

        $media->delete();

        return redirect()->route('admin.media.index')->with('success', 'Media file deleted successfully.');
    }
}
