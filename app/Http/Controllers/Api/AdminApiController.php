<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Media;
use App\Services\ElementRegistry;

class AdminApiController extends Controller
{
    protected $registry;

    public function __construct(ElementRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Admin login to get Sanctum API token.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.'
            ], 422);
        }

        // Only super_admin or content_manager allowed to write content
        if (!in_array($user->role, ['super_admin', 'content_manager'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Insufficient permissions.'
            ], 403);
        }

        // Create Sanctum Token
        $token = $user->createToken('admin-token')->plainTextToken;

        $secure = $request->isSecure() || env('APP_ENV') === 'production';
        $cookie = cookie(
            'lozybyte_admin_token',
            $token,
            120, // 2 hours
            '/',
            null, // domain
            $secure,
            true, // httpOnly
            false, // raw
            'Lax' // sameSite
        );

        return response()->json([
            'success' => true,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ])->withCookie($cookie);
    }

    /**
     * Verify token validity.
     */
    public function checkAuth(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'authenticated' => true,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ]);
    }

    /**
     * Save an element content, styles, or settings.
     */
    public function saveElement(Request $request)
    {
        $request->validate([
            'element_key' => 'required|string',
        ]);

        $key = $request->input('element_key');
        $lang = $request->header('Accept-Language', $request->input('lang', 'en'));
        $user = $request->user();

        // 1. Fetch old value configuration snapshot
        $oldElement = $this->registry->get($key, $lang);

        // 2. Perform the update
        $updated = $this->registry->save($key, $request->only(['content', 'styles', 'settings', 'metadata']), $lang);

        // 3. Log the change to audit logs table
        try {
            \App\Models\EditLog::create([
                'user_id' => $user ? $user->id : null,
                'user_name' => $user ? $user->name : 'Unknown User',
                'element_key' => $key,
                'action' => 'update',
                'old_val' => json_encode($oldElement),
                'new_val' => json_encode($updated),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to write edit log: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'element' => $updated
        ]);
    }

    /**
     * Upload media file.
     */
    public function uploadMedia(Request $request)
    {
        Cache::flush();
        $request->validate([
            'file' => 'required|file|image|max:10240', // 10MB limit
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Optimize image using Intervention Image
            $optimizedPath = $this->optimizeAndStoreImage($file);

            $media = Media::create([
                'filename' => $file->getClientOriginalName(),
                'file_path' => $optimizedPath,
                'file_type' => 'image/webp',
                'file_size' => file_exists(storage_path('app/public/' . $optimizedPath))
                    ? filesize(storage_path('app/public/' . $optimizedPath))
                    : $file->getSize(),
            ]);

            return response()->json([
                'success' => true,
                'url' => Storage::disk('public')->url($optimizedPath),
                'media' => $media
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No file uploaded.'
        ], 400);
    }

    /**
     * Optimize image and store as WebP.
     */
    private function optimizeAndStoreImage($file, $width = null, $height = null): string
    {
        $image = \Intervention\Image\Facades\Image::read($file->getRealPath());

        // Auto-orient based on EXIF
        if (method_exists($image, 'orientate')) {
            $image->orientate();
        }

        // Resize if dimensions provided, otherwise constrain to max 1920px
        if ($width && $height) {
            $image->fit($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        } else {
            $maxDim = 1920;
            if ($image->width() > $maxDim || $image->height() > $maxDim) {
                $image->resize($maxDim, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
        }

        // Generate unique filename
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $filename);
        $filename = $filename . '_' . uniqid() . '.webp';

        // Store optimized WebP
        $path = 'uploads/' . $filename;
        $image->toWebp(80)->save(storage_path('app/public/' . $path));

        // Generate thumbnail
        $thumbPath = 'uploads/thumb_' . $filename;
        $image->fit(400, 400, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->toWebp(75)->save(storage_path('app/public/' . $thumbPath));

        return $path;
    }

    /**
     * List all media assets.
     */
    public function listMedia()
    {
        $media = Media::latest()->paginate(36);
        return response()->json([
            'success' => true,
            'media' => $media
        ]);
    }

    /**
     * Delete media asset.
     */
    public function deleteMedia($id)
    {
        Cache::flush();
        $media = Media::findOrFail($id);

        // Parse path to delete from physical storage
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

        return response()->json([
            'success' => true,
            'message' => 'Media file deleted successfully.'
        ]);
    }

    /**
     * Admin logout to clear the Sanctum API token and cookie.
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->currentAccessToken()->delete();
        }

        $cookie = cookie()->forget('lozybyte_admin_token');

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.'
        ])->withCookie($cookie);
    }
}
