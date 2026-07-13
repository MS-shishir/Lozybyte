<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Testimonial;
use App\Http\Requests\Admin\TestimonialRequest;
use App\Traits\UploadsImage;

class TestimonialController extends Controller
{
    use UploadsImage;

    public function index(Request $request) {
        $query = Testimonial::query();
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('review', 'like', "%{$search}%");
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status') === 'active');
        }
        $testimonials = $query->latest()->paginate(10);
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create() {
        return view('admin.testimonials.create');
    }

    public function store(TestimonialRequest $request) {
        \Illuminate\Support\Facades\Cache::flush();
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->uploadImage($request->file('image'), 'testimonials', 400, 400); // Usually avatars are small
        }

        $videoPath = $this->storeVideo($request);

        Testimonial::create([
            'name' => $request->name,
            'designation' => ['en' => $request->designation_en, 'bn' => $request->designation_bn],
            'company' => $request->company,
            'review' => ['en' => $request->review_en, 'bn' => $request->review_bn],
            'rating' => $request->rating ?? 5,
            'image_path' => $imagePath,
            'video_url' => $request->video_url,
            'video_path' => $videoPath,
            'status' => $request->has('status')
        ]);
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial created.');
    }

    public function edit(Testimonial $testimonial) {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(TestimonialRequest $request, Testimonial $testimonial) {
        \Illuminate\Support\Facades\Cache::flush();
        if ($request->hasFile('image')) {
            $this->deleteImage($testimonial->image_path);
            $testimonial->image_path = $this->uploadImage($request->file('image'), 'testimonials', 400, 400);
        }

        $videoPath = $this->storeVideo($request, $testimonial);

        $testimonial->update([
            'name' => $request->name,
            'designation' => ['en' => $request->designation_en, 'bn' => $request->designation_bn],
            'company' => $request->company,
            'review' => ['en' => $request->review_en, 'bn' => $request->review_bn],
            'rating' => $request->rating ?? 5,
            'video_url' => $request->video_url,
            'video_path' => $videoPath,
            'status' => $request->has('status')
        ]);
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated.');
    }

    /**
     * Store an uploaded video file and return its storage path.
     * Existing video is removed when a new one is uploaded.
     */
    private function storeVideo(Request $request, ?Testimonial $testimonial = null)
    {
        if (!$request->hasFile('video')) {
            return $testimonial ? $testimonial->video_path : null;
        }

        if ($testimonial && $testimonial->video_path) {
            Storage::disk('public')->delete($testimonial->video_path);
        }

        $file = $request->file('video');
        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = 'testimonials/videos/' . $filename;
        $file->storeAs('testimonials/videos', $filename, 'public');

        return $path;
    }

    public function destroy(Testimonial $testimonial) {
        \Illuminate\Support\Facades\Cache::flush();
        $this->deleteImage($testimonial->image_path);
        $testimonial->delete();
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted.');
    }
}
