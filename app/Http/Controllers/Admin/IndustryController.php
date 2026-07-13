<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Industry;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\IndustryRequest;
use Illuminate\Support\Facades\Cache;

class IndustryController extends Controller
{
    public function index(Request $request)
    {
        $query = Industry::query();
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status') === 'active');
        }
        $industries = $query->orderBy('sort_order', 'asc')->latest()->paginate(20);
        return view('admin.industries.index', compact('industries'));
    }

    public function store(IndustryRequest $request)
    {
        Cache::flush();

        Industry::create([
            'slug' => Str::slug($request->slug),
            'icon' => $request->icon ?? 'Building2',
            'title' => ['en' => $request->title_en, 'bn' => $request->title_bn],
            'description' => ['en' => $request->desc_en, 'bn' => $request->desc_bn],
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->has('status'),
        ]);

        return redirect()->route('admin.industries.index')->with('success', 'Industry added successfully.');
    }

    public function update(IndustryRequest $request, Industry $industry)
    {
        Cache::flush();

        $industry->update([
            'slug' => Str::slug($request->slug),
            'icon' => $request->icon ?? 'Building2',
            'title' => ['en' => $request->title_en, 'bn' => $request->title_bn],
            'description' => ['en' => $request->desc_en, 'bn' => $request->desc_bn],
            'sort_order' => $request->sort_order ?? $industry->sort_order,
            'status' => $request->has('status'),
        ]);

        return redirect()->route('admin.industries.index')->with('success', 'Industry updated successfully.');
    }

    public function destroy(Industry $industry)
    {
        Cache::flush();
        $industry->delete();
        return redirect()->route('admin.industries.index')->with('success', 'Industry deleted successfully.');
    }
}
