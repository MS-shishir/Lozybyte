<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PricingPlan;
use Illuminate\Support\Facades\Cache;

class PricingPlanController extends Controller
{
    public function index(Request $request)
    {
        $plans = PricingPlan::orderBy('sort_order')->orderBy('created_at')->get();
        return view('admin.pricing.index', compact('plans'));
    }

    public function create()
    {
        return redirect()->route('admin.pricing.index');
    }

    public function store(Request $request)
    {
        Cache::flush();

        $featuresEn = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $request->features_en ?? '')))));
        $featuresBn = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $request->features_bn ?? '')))));

        PricingPlan::create([
            'name'           => ['en' => $request->name_en, 'bn' => $request->name_bn],
            'category'       => ['en' => $request->category_en, 'bn' => $request->category_bn],
            'tagline'        => ['en' => $request->tagline_en, 'bn' => $request->tagline_bn],
            'badge'          => ['en' => $request->badge_en, 'bn' => $request->badge_bn],
            'description'    => ['en' => $request->description_en, 'bn' => $request->description_bn],
            'features'       => ['en' => $featuresEn, 'bn' => $featuresBn],
            'price_monthly'  => $request->price_monthly,
            'price_yearly'   => $request->price_yearly,
            'price_lifetime' => $request->price_lifetime,
            'link_monthly'   => $request->link_monthly,
            'link_yearly'    => $request->link_yearly,
            'link_lifetime'  => $request->link_lifetime,
            'color'          => $request->color ?? '#6366f1',
            'is_featured'    => $request->has('is_featured'),
            'status'         => $request->has('status'),
            'sort_order'     => (int) ($request->sort_order ?? 0),
        ]);

        return redirect()->route('admin.pricing.index')->with('success', 'Pricing plan created successfully.');
    }

    public function edit(PricingPlan $pricing)
    {
        return redirect()->route('admin.pricing.index');
    }

    public function update(Request $request, PricingPlan $pricing)
    {
        Cache::flush();

        $featuresEn = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $request->features_en ?? '')))));
        $featuresBn = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $request->features_bn ?? '')))));

        $pricing->update([
            'name'           => ['en' => $request->name_en, 'bn' => $request->name_bn],
            'category'       => ['en' => $request->category_en, 'bn' => $request->category_bn],
            'tagline'        => ['en' => $request->tagline_en, 'bn' => $request->tagline_bn],
            'badge'          => ['en' => $request->badge_en, 'bn' => $request->badge_bn],
            'description'    => ['en' => $request->description_en, 'bn' => $request->description_bn],
            'features'       => ['en' => $featuresEn, 'bn' => $featuresBn],
            'price_monthly'  => $request->price_monthly,
            'price_yearly'   => $request->price_yearly,
            'price_lifetime' => $request->price_lifetime,
            'link_monthly'   => $request->link_monthly,
            'link_yearly'    => $request->link_yearly,
            'link_lifetime'  => $request->link_lifetime,
            'color'          => $request->color ?? '#6366f1',
            'is_featured'    => $request->has('is_featured'),
            'status'         => $request->has('status'),
            'sort_order'     => (int) ($request->sort_order ?? 0),
        ]);

        return redirect()->route('admin.pricing.index')->with('success', 'Pricing plan updated successfully.');
    }

    public function destroy(PricingPlan $pricing)
    {
        Cache::flush();
        $pricing->delete();
        return redirect()->route('admin.pricing.index')->with('success', 'Pricing plan deleted.');
    }
}
