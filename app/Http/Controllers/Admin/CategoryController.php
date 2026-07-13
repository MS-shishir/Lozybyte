<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\CategoryRequest;

class CategoryController extends Controller
{
    public function store(CategoryRequest $request) {
        \Illuminate\Support\Facades\Cache::flush();
        Category::create([
            'name' => ['en' => $request->name_en, 'bn' => $request->name_bn],
            'slug' => Str::slug($request->slug)
        ]);
        return redirect()->back()->with('success', 'Category created.');
    }

    public function destroy(Category $category) {
        \Illuminate\Support\Facades\Cache::flush();
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted.');
    }
}
