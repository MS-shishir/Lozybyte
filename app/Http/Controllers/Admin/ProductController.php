<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\ProductRequest;
use App\Traits\UploadsImage;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    use UploadsImage;

    public function index(Request $request) {
        $query = Product::query();
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status') === 'active');
        }
        $products = $query->orderBy('sort_order', 'asc')->latest()->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    public function create() {
        return redirect()->route('admin.products.index');
    }

    public function store(ProductRequest $request) {
        Cache::flush();
        $screenshots = [];
        if ($request->hasFile('screenshots')) {
            foreach ($request->file('screenshots') as $file) {
                $screenshots[] = $this->uploadImagePath($file, 'products');
            }
        }

        $featuresEn = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $request->features_en)))));
        $featuresBn = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $request->features_bn)))));

        Product::create([
            'title' => ['en' => $request->title_en, 'bn' => $request->title_bn],
            'slug' => Str::slug($request->slug),
            'category' => $request->category,
            'pricing' => [
                'monthly' => ['price' => $request->price_monthly, 'link' => $request->link_monthly],
                'yearly' => ['price' => $request->price_yearly, 'link' => $request->link_yearly],
                'lifetime' => ['price' => $request->price_lifetime, 'link' => $request->link_lifetime]
            ],
            'demo_url' => $request->demo_url,
            'features' => ['en' => $featuresEn, 'bn' => $featuresBn],
            'screenshots' => $screenshots,
            'status' => $request->has('status'),
            'icon' => $request->icon ?? 'Package',
            'color' => $request->color ?? '#6366f1',
            'tagline' => ['en' => $request->tagline_en, 'bn' => $request->tagline_bn],
            'badge' => ['en' => $request->badge_en, 'bn' => $request->badge_bn],
            'badge_color' => $request->badge_color ?? $request->color ?? '#6366f1',
            'description' => ['en' => $request->description_en, 'bn' => $request->description_bn],
            'clients_count' => $request->clients_count ?? 0,
            'rating' => $request->rating ?? 4.8,
            'screenshot_type' => $request->screenshot_type ?? 'school',
            'sort_order' => $request->sort_order ?? 0,
        ]);
        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product) {
        return redirect()->route('admin.products.index');
    }

    public function update(ProductRequest $request, Product $product) {
        Cache::flush();
        $screenshots = $product->screenshots ?? [];
        if ($request->hasFile('screenshots')) {
            foreach ($request->file('screenshots') as $file) {
                $screenshots[] = $this->uploadImagePath($file, 'products');
            }
        }

        // Allow removing existing screenshots by index
        if ($request->has('remove_screenshots') && is_array($request->remove_screenshots)) {
            foreach ($request->remove_screenshots as $index) {
                if (isset($screenshots[$index])) {
                    $this->deleteImage($screenshots[$index]);
                    unset($screenshots[$index]);
                }
            }
            $screenshots = array_values($screenshots);
        }

        $featuresEn = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $request->features_en)))));
        $featuresBn = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $request->features_bn)))));

        $product->update([
            'title' => ['en' => $request->title_en, 'bn' => $request->title_bn],
            'slug' => Str::slug($request->slug),
            'category' => $request->category,
            'pricing' => [
                'monthly' => ['price' => $request->price_monthly, 'link' => $request->link_monthly],
                'yearly' => ['price' => $request->price_yearly, 'link' => $request->link_yearly],
                'lifetime' => ['price' => $request->price_lifetime, 'link' => $request->link_lifetime]
            ],
            'demo_url' => $request->demo_url,
            'features' => ['en' => $featuresEn, 'bn' => $featuresBn],
            'screenshots' => $screenshots,
            'status' => $request->has('status'),
            'icon' => $request->icon ?? 'Package',
            'color' => $request->color ?? '#6366f1',
            'tagline' => ['en' => $request->tagline_en, 'bn' => $request->tagline_bn],
            'badge' => ['en' => $request->badge_en, 'bn' => $request->badge_bn],
            'badge_color' => $request->badge_color ?? $request->color ?? '#6366f1',
            'description' => ['en' => $request->description_en, 'bn' => $request->description_bn],
            'clients_count' => $request->clients_count ?? 0,
            'rating' => $request->rating ?? 4.8,
            'screenshot_type' => $request->screenshot_type ?? 'school',
            'sort_order' => $request->sort_order ?? 0,
        ]);
        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product) {
        Cache::flush();
        if ($product->screenshots) {
            foreach ($product->screenshots as $screenshot) {
                $this->deleteImage($screenshot);
            }
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }
}
