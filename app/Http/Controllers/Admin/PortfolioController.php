<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Portfolio;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\PortfolioRequest;
use App\Traits\UploadsImage;

class PortfolioController extends Controller
{
    use UploadsImage;

    public function index(Request $request) {
        $query = Portfolio::query();
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('client', 'like', "%{$search}%")
                  ->orWhere('industry', 'like', "%{$search}%");
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status') === 'active');
        }
        $portfolios = $query->latest()->paginate(10);
        return view('admin.portfolios.index', compact('portfolios'));
    }

    public function create() {
        return view('admin.portfolios.create');
    }

    public function store(PortfolioRequest $request) {
        \Illuminate\Support\Facades\Cache::flush();
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->uploadImage($request->file('image'), 'portfolios');
        }

        $meta = [
            'color' => $request->color ?? '#6366f1',
            'logo_color' => $request->logo_color ?? '#06b6d4',
            'logo_text' => $request->logo_text ?? '',
            'logo_icon' => $request->logo_icon ?? 'Award',
            'duration' => $request->duration ?? '',
            'team' => $request->team ?? '',
            'launched' => $request->launched ?? '',
            'tag' => [
                'en' => $request->tag_en ?? '',
                'bn' => $request->tag_bn ?? '',
            ],
            'stats' => [
                'en' => array_values(array_filter($request->stats_en ?? [])),
                'bn' => array_values(array_filter($request->stats_bn ?? [])),
            ],
            'tech' => array_values(array_filter($request->tech ?? [])),
            'metrics' => array_values(array_filter($request->metrics ?? [])),
        ];

        Portfolio::create([
            'title' => ['en' => $request->title_en, 'bn' => $request->title_bn],
            'slug' => Str::slug($request->slug),
            'client' => $request->client,
            'industry' => ['en' => $request->industry_en, 'bn' => $request->industry_bn],
            'challenge' => ['en' => $request->challenge_en, 'bn' => $request->challenge_bn],
            'solution' => ['en' => $request->solution_en, 'bn' => $request->solution_bn],
            'result' => ['en' => $request->result_en, 'bn' => $request->result_bn],
            'image_path' => $imagePath,
            'meta' => $meta,
            'status' => $request->has('status')
        ]);
        return redirect()->route('admin.portfolios.index')->with('success', 'Case Study created successfully.');
    }

    public function edit(Portfolio $portfolio) {
        return view('admin.portfolios.edit', compact('portfolio'));
    }

    public function update(PortfolioRequest $request, Portfolio $portfolio) {
        \Illuminate\Support\Facades\Cache::flush();
        $imagePath = $portfolio->image_path;
        if ($request->hasFile('image')) {
            $this->deleteImage($portfolio->image_path);
            $imagePath = $this->uploadImage($request->file('image'), 'portfolios');
        }

        $meta = [
            'color' => $request->color ?? '#6366f1',
            'logo_color' => $request->logo_color ?? '#06b6d4',
            'logo_text' => $request->logo_text ?? '',
            'logo_icon' => $request->logo_icon ?? 'Award',
            'duration' => $request->duration ?? '',
            'team' => $request->team ?? '',
            'launched' => $request->launched ?? '',
            'tag' => [
                'en' => $request->tag_en ?? '',
                'bn' => $request->tag_bn ?? '',
            ],
            'stats' => [
                'en' => array_values(array_filter($request->stats_en ?? [])),
                'bn' => array_values(array_filter($request->stats_bn ?? [])),
            ],
            'tech' => array_values(array_filter($request->tech ?? [])),
            'metrics' => array_values(array_filter($request->metrics ?? [])),
        ];

        $portfolio->update([
            'title' => ['en' => $request->title_en, 'bn' => $request->title_bn],
            'slug' => Str::slug($request->slug),
            'client' => $request->client,
            'industry' => ['en' => $request->industry_en, 'bn' => $request->industry_bn],
            'challenge' => ['en' => $request->challenge_en, 'bn' => $request->challenge_bn],
            'solution' => ['en' => $request->solution_en, 'bn' => $request->solution_bn],
            'result' => ['en' => $request->result_en, 'bn' => $request->result_bn],
            'image_path' => $imagePath,
            'meta' => $meta,
            'status' => $request->has('status')
        ]);
        return redirect()->route('admin.portfolios.index')->with('success', 'Case Study updated successfully.');
    }

    public function destroy(Portfolio $portfolio) {
        \Illuminate\Support\Facades\Cache::flush();
        $this->deleteImage($portfolio->image_path);
        $portfolio->delete();
        return redirect()->route('admin.portfolios.index')->with('success', 'Case Study deleted.');
    }
}
