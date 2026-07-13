<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\ServiceRequest;
use App\Traits\UploadsImage;
use Illuminate\Support\Facades\Cache;

class ServiceController extends Controller
{
    use UploadsImage;

    public function index(Request $request)
    {
        $query = Service::query();
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status') === 'active');
        }
        $services = $query->orderBy('sort_order', 'asc')->latest()->paginate(20);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(ServiceRequest $request)
    {
        Cache::flush();

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->uploadImage($request->file('image'), 'services');
        }

        // Parse comma-separated strings into arrays
        $featuresEn    = $this->parseList($request->features_en);
        $featuresBn    = $this->parseList($request->features_bn);
        $stepsEn       = $this->parseList($request->process_steps_en);
        $stepsBn       = $this->parseList($request->process_steps_bn);

        Service::create([
            'title'          => ['en' => $request->title_en, 'bn' => $request->title_bn],
            'slug'           => Str::slug($request->slug),
            'icon'           => $request->icon,
            'color'          => $request->color ?? '#6366f1',
            'glow_color'     => $request->glow_color ?? 'rgba(99,102,241,0.2)',
            'image_path'     => $imagePath,
            'description'    => ['en' => $request->desc_en, 'bn' => $request->desc_bn],
            'details'        => ['en' => $request->details_en, 'bn' => $request->details_bn],
            'timeline'       => $request->timeline,
            'starting_price' => $request->starting_price,
            'case_result'    => ['en' => $request->case_result_en, 'bn' => $request->case_result_bn],
            'features'       => ['en' => $featuresEn, 'bn' => $featuresBn],
            'process_steps'  => ['en' => $stepsEn, 'bn' => $stepsBn],
            'sort_order'     => $request->sort_order ?? 0,
            'status'         => $request->has('status'),
        ]);

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(ServiceRequest $request, Service $service)
    {
        Cache::flush();

        if ($request->hasFile('image')) {
            $this->deleteImage($service->image_path);
            $service->image_path = $this->uploadImage($request->file('image'), 'services');
        }

        $featuresEn    = $this->parseList($request->features_en);
        $featuresBn    = $this->parseList($request->features_bn);
        $stepsEn       = $this->parseList($request->process_steps_en);
        $stepsBn       = $this->parseList($request->process_steps_bn);

        $service->update([
            'title'          => ['en' => $request->title_en, 'bn' => $request->title_bn],
            'slug'           => Str::slug($request->slug),
            'icon'           => $request->icon,
            'color'          => $request->color ?? '#6366f1',
            'glow_color'     => $request->glow_color ?? 'rgba(99,102,241,0.2)',
            'image_path'     => $service->image_path,
            'description'    => ['en' => $request->desc_en, 'bn' => $request->desc_bn],
            'details'        => ['en' => $request->details_en, 'bn' => $request->details_bn],
            'timeline'       => $request->timeline,
            'starting_price' => $request->starting_price,
            'case_result'    => ['en' => $request->case_result_en, 'bn' => $request->case_result_bn],
            'features'       => ['en' => $featuresEn, 'bn' => $featuresBn],
            'process_steps'  => ['en' => $stepsEn, 'bn' => $stepsBn],
            'sort_order'     => $request->sort_order ?? $service->sort_order,
            'status'         => $request->has('status'),
        ]);

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        Cache::flush();
        $this->deleteImage($service->image_path);
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Service deleted.');
    }

    /**
     * Parse a newline or comma-separated string into a clean array.
     */
    private function parseList(?string $input): array
    {
        if (empty($input)) return [];
        // Support both newline and comma separation
        $delimiter = str_contains($input, "\n") ? "\n" : ",";
        return array_values(array_filter(array_map('trim', explode($delimiter, $input))));
    }
}
