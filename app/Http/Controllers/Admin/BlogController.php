<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\PostRequest;
use App\Traits\UploadsImage;

class BlogController extends Controller
{
    use UploadsImage;

    public function index(Request $request) {
        $query = Post::with('category');
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status') === 'active');
        }
        $posts = $query->latest()->paginate(10);
        $categories = Category::all();
        return view('admin.blog.index', compact('posts', 'categories'));
    }

    public function create() {
        $categories = Category::all();
        return view('admin.blog.create', compact('categories'));
    }

    public function store(PostRequest $request) {
        \Illuminate\Support\Facades\Cache::flush();
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->uploadImage($request->file('image'), 'blog');
        }

        Post::create([
            'category_id' => $request->category_id,
            'author_id' => auth()->id(),
            'title' => ['en' => $request->title_en, 'bn' => $request->title_bn],
            'slug' => Str::slug($request->slug),
            'content' => ['en' => $request->content_en, 'bn' => $request->content_bn],
            'image_path' => $imagePath,
            'seo' => [
                'meta_title' => ['en' => $request->seo_title_en, 'bn' => $request->seo_title_bn],
                'meta_description' => ['en' => $request->seo_desc_en, 'bn' => $request->seo_desc_bn]
            ],
            'status' => $request->has('status')
        ]);
        return redirect()->route('admin.blog.index')->with('success', 'Blog post created.');
    }

    public function edit(Post $post) {
        $categories = Category::all();
        return view('admin.blog.edit', compact('post', 'categories'));
    }

    public function update(PostRequest $request, Post $post) {
        \Illuminate\Support\Facades\Cache::flush();
        if ($request->hasFile('image')) {
            $this->deleteImage($post->image_path);
            $post->image_path = $this->uploadImage($request->file('image'), 'blog');
        }

        $post->update([
            'category_id' => $request->category_id,
            'title' => ['en' => $request->title_en, 'bn' => $request->title_bn],
            'slug' => Str::slug($request->slug),
            'content' => ['en' => $request->content_en, 'bn' => $request->content_bn],
            'seo' => [
                'meta_title' => ['en' => $request->seo_title_en, 'bn' => $request->seo_title_bn],
                'meta_description' => ['en' => $request->seo_desc_en, 'bn' => $request->seo_desc_bn]
            ],
            'status' => $request->has('status')
        ]);
        return redirect()->route('admin.blog.index')->with('success', 'Blog post updated.');
    }

    public function destroy(Post $post) {
        \Illuminate\Support\Facades\Cache::flush();
        $this->deleteImage($post->image_path);
        $post->delete();
        return redirect()->route('admin.blog.index')->with('success', 'Blog post deleted.');
    }
}
