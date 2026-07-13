<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Page;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $query = Page::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('slug', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status') === 'active');
        }

        $pages = $query->latest()->paginate(15);
        return view('admin.pages.index', compact('pages'));
    }

    public function store(Request $request)
    {
        \Illuminate\Support\Facades\Cache::flush();
        $request->validate([
            'title_en' => 'required|string',
            'slug' => 'required|string|unique:pages,slug',
            'content_en' => 'required|string',
            'og_image' => 'nullable|image|max:2048',
            'banner_image' => 'nullable|image|max:2048'
        ]);

        $bannerImage = null;
        if ($request->hasFile('banner_image')) {
            $bannerImage = \Illuminate\Support\Facades\Storage::disk('public')->url($request->file('banner_image')->store('uploads', 'public'));
        }

        $ogImage = null;
        if ($request->hasFile('og_image')) {
            $ogImage = \Illuminate\Support\Facades\Storage::disk('public')->url($request->file('og_image')->store('uploads', 'public'));
        }

        Page::create([
            'title' => [
                'en' => $request->input('title_en'),
                'bn' => $request->input('title_bn') ?: $request->input('title_en')],
            'slug' => Str::slug($request->input('slug')),
            'banner_image' => $bannerImage,
            'content' => [
                'en' => $request->input('content_en'),
                'bn' => $request->input('content_bn') ?: $request->input('content_en')],
            'seo' => [
                'meta_title' => [
                    'en' => $request->input('seo_title_en') ?: $request->input('title_en'),
                    'bn' => $request->input('seo_title_bn') ?: $request->input('title_bn')
                ],
                'meta_description' => [
                    'en' => $request->input('seo_desc_en'),
                    'bn' => $request->input('seo_desc_bn')
                ],
                'og_title' => [
                    'en' => $request->input('og_title_en'),
                    'bn' => $request->input('og_title_bn')
                ],
                'og_description' => [
                    'en' => $request->input('og_desc_en'),
                    'bn' => $request->input('og_desc_bn')
                ],
                'og_image' => $ogImage
            ],
            'status' => $request->has('status')
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Custom Page created successfully.');
    }

    public function update(Request $request, Page $page)
    {
        \Illuminate\Support\Facades\Cache::flush();
        $request->validate([
            'title_en' => 'required|string',
            'slug' => 'required|string|unique:pages,slug,' . $page->id,
            'content_en' => 'required|string',
            'og_image' => 'nullable|image|max:2048',
            'banner_image' => 'nullable|image|max:2048'
        ]);

        $bannerImage = $page->banner_image;
        if ($request->hasFile('banner_image')) {
            $bannerImage = \Illuminate\Support\Facades\Storage::disk('public')->url($request->file('banner_image')->store('uploads', 'public'));
        }

        $pageSeo = $page->seo ?: [];
        $ogImage = $pageSeo['og_image'] ?? null;
        if ($request->hasFile('og_image')) {
            // Delete previous OG image file if it exists
            if ($ogImage) {
                $prevPath = str_replace(asset('storage/'), '', $ogImage);
                $prevPath = ltrim($prevPath, '/');
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($prevPath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($prevPath);
                }
            }
            $ogImage = \Illuminate\Support\Facades\Storage::disk('public')->url($request->file('og_image')->store('uploads', 'public'));
        }

        $page->update([
            'title' => [
                'en' => $request->input('title_en'),
                'bn' => $request->input('title_bn') ?: $request->input('title_en')],
            'slug' => Str::slug($request->input('slug')),
            'banner_image' => $bannerImage,
            'content' => [
                'en' => $request->input('content_en'),
                'bn' => $request->input('content_bn') ?: $request->input('content_en')],
            'seo' => [
                'meta_title' => [
                    'en' => $request->input('seo_title_en') ?: $request->input('title_en'),
                    'bn' => $request->input('seo_title_bn') ?: $request->input('title_bn')
                ],
                'meta_description' => [
                    'en' => $request->input('seo_desc_en'),
                    'bn' => $request->input('seo_desc_bn')
                ],
                'og_title' => [
                    'en' => $request->input('og_title_en'),
                    'bn' => $request->input('og_title_bn')
                ],
                'og_description' => [
                    'en' => $request->input('og_desc_en'),
                    'bn' => $request->input('og_desc_bn')
                ],
                'og_image' => $ogImage
            ],
            'status' => $request->has('status')
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Custom Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        \Illuminate\Support\Facades\Cache::flush();
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Custom Page deleted.');
    }
}
