<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use Illuminate\Http\Request;
use App\Traits\UploadsImage;

class HomepageSectionController extends Controller
{
    use UploadsImage;

    public function index()
    {
        $sections = HomepageSection::orderBy('sort_order', 'asc')->get();
        return view('admin.founder_and_vision.index', compact('sections'));
    }

    public function create()
    {
        return view('admin.founder_and_vision.create');
    }

    public function store(Request $request)
    {
        \Illuminate\Support\Facades\Cache::flush();
        $validated = $request->validate([
            'key' => 'required|string|unique:homepage_sections,key',
            'title_en' => 'nullable|string',
            'title_bn' => 'nullable|string',

            'subtitle_en' => 'nullable|string',
            'subtitle_bn' => 'nullable|string',

            'description_en' => 'nullable|string',
            'description_bn' => 'nullable|string',

            'button_text_en' => 'nullable|string',
            'button_text_bn' => 'nullable|string',

            'button_url' => 'nullable|string',
            'background_image' => 'nullable|image|max:2048',
            'main_image' => ($request->input('key') === 'founder_story') ? 'nullable|file|mimes:mp4,mov,ogg,qt,webm|max:20480' : 'nullable|image|max:2048',
            'sort_order' => 'required|integer',
            'visible' => 'nullable|in:active',
        ]);

        $section = new HomepageSection();
        $section->key = $validated['key'];
        
        $section->title = [
            'en' => $validated['title_en'] ?? null,
            'bn' => $validated['title_bn'] ?? null
        ];
        $section->subtitle = [
            'en' => $validated['subtitle_en'] ?? null,
            'bn' => $validated['subtitle_bn'] ?? null
        ];
        $section->description = [
            'en' => $validated['description_en'] ?? null,
            'bn' => $validated['description_bn'] ?? null
        ];
        $section->button_text = [
            'en' => $validated['button_text_en'] ?? null,
            'bn' => $validated['button_text_bn'] ?? null
        ];
        
        $section->button_url = $validated['button_url'] ?? null;
        $section->sort_order = $validated['sort_order'];
        $section->visible = $request->has('visible');

        if ($request->hasFile('background_image')) {
            $section->background_image = $this->uploadImage($request->file('background_image'), 'sections');
        }

        if ($request->hasFile('main_image')) {
            if ($section->key === 'founder_story') {
                $file = $request->file('main_image');
                $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $fullPath = 'sections/' . $filename;
                \Illuminate\Support\Facades\Storage::disk('public')->putFileAs('sections', $file, $filename);
                $section->main_image = \Illuminate\Support\Facades\Storage::disk('public')->url($fullPath);
            } else {
                $section->main_image = $this->uploadImage($request->file('main_image'), 'sections');
            }
        }

        $section->save();

        // Clear API Cache
        \Illuminate\Support\Facades\Cache::forget('api_homepage_sections');

        return redirect()->route('admin.homepage-sections.index')->with('success', 'Section created successfully!');
    }

    public function editFounderVision()
    {
        $homepage_section = HomepageSection::where('key', 'founder_story')->first();
        if (!$homepage_section) {
            abort(404, 'Founder & Vision section not found.');
        }
        return view('admin.founder_and_vision.edit', compact('homepage_section'));
    }

    public function edit(HomepageSection $homepage_section)
    {
        return view('admin.founder_and_vision.edit', compact('homepage_section'));
    }

    public function update(Request $request, HomepageSection $homepage_section)
    {
        \Illuminate\Support\Facades\Cache::flush();
        $validated = $request->validate([
            'key' => 'required|string|unique:homepage_sections,key,' . $homepage_section->id,
            'title_en' => 'nullable|string',
            'title_bn' => 'nullable|string',

            'subtitle_en' => 'nullable|string',
            'subtitle_bn' => 'nullable|string',

            'description_en' => 'nullable|string',
            'description_bn' => 'nullable|string',

            'button_text_en' => 'nullable|string',
            'button_text_bn' => 'nullable|string',

            'button_url' => 'nullable|string',
            'background_image' => 'nullable|image|max:2048',
            'main_image' => ($request->input('key') === 'founder_story') ? 'nullable|file|mimes:mp4,mov,ogg,qt,webm|max:20480' : 'nullable|image|max:2048',
            'sort_order' => 'required|integer',
            'visible' => 'nullable|in:active',
        ]);

        $homepage_section->key = $validated['key'];
        
        $homepage_section->title = [
            'en' => $validated['title_en'] ?? null,
            'bn' => $validated['title_bn'] ?? null
        ];
        $homepage_section->subtitle = [
            'en' => $validated['subtitle_en'] ?? null,
            'bn' => $validated['subtitle_bn'] ?? null
        ];
        $homepage_section->description = [
            'en' => $validated['description_en'] ?? null,
            'bn' => $validated['description_bn'] ?? null
        ];
        $homepage_section->button_text = [
            'en' => $validated['button_text_en'] ?? null,
            'bn' => $validated['button_text_bn'] ?? null
        ];

        $homepage_section->button_url = $validated['button_url'] ?? null;
        $homepage_section->sort_order = $validated['sort_order'];
        $homepage_section->visible = $request->has('visible');

        if ($request->hasFile('background_image')) {
            if ($homepage_section->background_image) {
                $this->deleteImage($homepage_section->background_image);
            }
            $homepage_section->background_image = $this->uploadImage($request->file('background_image'), 'sections');
        }

        if ($request->hasFile('main_image')) {
            if ($homepage_section->main_image) {
                $prevPath = str_replace(asset('storage/'), '', $homepage_section->main_image);
                $prevPath = ltrim($prevPath, '/');
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($prevPath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($prevPath);
                } else {
                    $this->deleteImage($homepage_section->main_image);
                }
            }
            if ($homepage_section->key === 'founder_story') {
                $file = $request->file('main_image');
                $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $fullPath = 'sections/' . $filename;
                \Illuminate\Support\Facades\Storage::disk('public')->putFileAs('sections', $file, $filename);
                $homepage_section->main_image = \Illuminate\Support\Facades\Storage::disk('public')->url($fullPath);
            } else {
                $homepage_section->main_image = $this->uploadImage($request->file('main_image'), 'sections');
            }
        }

        $homepage_section->save();

        // Clear API Cache
        \Illuminate\Support\Facades\Cache::forget('api_homepage_sections');

        if ($homepage_section->key === 'founder_story') {
            return redirect()->route('admin.founder-and-vision.edit')->with('success', 'Founder & Vision updated successfully!');
        }
        return redirect()->route('admin.homepage-sections.edit', $homepage_section->id)->with('success', 'Section updated successfully!');
    }

    public function destroy(HomepageSection $homepage_section)
    {
        \Illuminate\Support\Facades\Cache::flush();
        if ($homepage_section->background_image) {
            $this->deleteImage($homepage_section->background_image);
        }
        if ($homepage_section->main_image) {
            $this->deleteImage($homepage_section->main_image);
        }
        
        $homepage_section->delete();
        \Illuminate\Support\Facades\Cache::forget('api_homepage_sections');
        
        return redirect()->route('admin.homepage-sections.index')->with('success', 'Section deleted successfully!');
    }
}
