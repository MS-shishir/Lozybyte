<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\SettingUpdateRequest;
use App\Models\Setting;
use App\Models\HomepageSection;
use App\Traits\UploadsImage;

class SettingController extends Controller
{
    use UploadsImage;
    public function edit()
    {
        $settings = Setting::firstOrCreate([], [
            'company_name' => ['en' => 'Lozybyte', 'bn' => 'লজিইবাইট'],
            'tagline' => ['en' => 'We Build Digital Businesses', 'bn' => 'আমরা ডিজিটাল ব্যবসা তৈরি করি'],
            'email' => 'info@lozybyte.com',
            'phone' => '+8801700000000',
            'address' => ['en' => 'Dhanmondi, Dhaka', 'bn' => 'ধানমন্ডি, ঢাকা'],
            'social_links' => ['facebook' => '', 'twitter' => '', 'linkedin' => '', 'github' => ''],
            'theme_config' => [
                'primary_color' => '#6366f1', 
                'secondary_color' => '#06b6d4', 
                'accent_color' => '#a855f7',
                'btn_grad_start' => '#a855f7',
                'btn_grad_end' => '#6366f1',
                'btn_grad_start_light' => '#a855f7',
                'btn_grad_end_light' => '#6366f1',
                'font_family' => 'Outfit', 
                'button_style' => 'rounded-full', 
                'default_mode' => 'dark',
                'navbar_cta_text' => ['en' => 'Start Project', 'bn' => 'প্রজেক্ট শুরু করুন'],
                'navbar_cta_url' => '#contact'
            ],
            'seo_defaults' => [
                'meta_title' => ['en' => 'Lozybyte', 'bn' => 'লজিইবাইট'],
                'meta_description' => ['en' => 'Software Company', 'bn' => 'সফটওয়্যার কোম্পানি'],
                'og_image' => '',
            'og_image' => '',
                'schema_markup' => '',
                'keywords' => '',
                'robots' => '',
                'canonical_url' => ''
            ],
            'google_analytics_id' => 'G-XXXXXXXXXX',
            'google_tag_manager_id' => '',
            'facebook_pixel_id' => '',
            'custom_scripts' => '',
            'footer_scripts' => ''
        ]);

        $sections = HomepageSection::orderBy('sort_order', 'asc')->get();

        return view('admin.settings.edit', compact('settings', 'sections'));
    }

    public function update(SettingUpdateRequest $request)
    {
        \Illuminate\Support\Facades\Cache::flush();
        $settings = Setting::first();

        // 1. Core and Localized Information
        $settings->website_name = [
            'en' => $request->input('website_name_en'),
            'bn' => $request->input('website_name_bn')];

        $settings->company_name = [
            'en' => $request->input('company_name_en'),
            'bn' => $request->input('company_name_bn')];

        $settings->tagline = [
            'en' => $request->input('tagline_en'),
            'bn' => $request->input('tagline_bn')];

        $settings->company_description = [
            'en' => $request->input('company_desc_en'),
            'bn' => $request->input('company_desc_bn')];

        $settings->address = [
            'en' => $request->input('address_en'),
            'bn' => $request->input('address_bn')];
        
        $settings->business_hours = [
            'en' => $request->input('business_hours_en'),
            'bn' => $request->input('business_hours_bn')];

        $settings->email = $request->input('email');
        $settings->phone = $request->input('phone');
        $settings->whatsapp = $request->input('whatsapp');
        $settings->google_map_embed = $request->input('google_map_embed');

        // Handle File Uploads (Favicon / Logo / Footer Logo / Dark / Mobile)
        if ($request->hasFile('logo')) {
            $settings->logo_path = $this->uploadImage($request->file('logo'), 'settings');
        }
        if ($request->hasFile('dark_logo')) {
            $settings->dark_logo_path = $this->uploadImage($request->file('dark_logo'), 'settings');
        }
        if ($request->hasFile('mobile_logo')) {
            $settings->mobile_logo_path = $this->uploadImage($request->file('mobile_logo'), 'settings');
        }
        if ($request->hasFile('favicon')) {
            // Usually favicons shouldn't be converted to WebP heavily, but for consistency we use uploadImage.
            $settings->favicon_path = $this->uploadImage($request->file('favicon'), 'settings');
        }
        if ($request->hasFile('footer_logo')) {
            $settings->footer_logo_path = $this->uploadImage($request->file('footer_logo'), 'settings');
        }

        // 2. Social Links
        $settings->social_links = [
            'facebook' => $request->input('social_facebook'),
            'twitter' => $request->input('social_twitter'),
            'linkedin' => $request->input('social_linkedin'),
            'github' => $request->input('social_github'),
            'instagram' => $request->input('social_instagram'),
            'youtube' => $request->input('social_youtube'),
            'tiktok' => $request->input('social_tiktok'),
            'telegram' => $request->input('social_telegram'),
            'messenger' => $request->input('social_messenger'),
        ];

        // 3. Theme Configuration
        $settings->theme_config = [
            'primary_color' => $request->input('theme_primary_color', '#6366f1'),
            'secondary_color' => $request->input('theme_secondary_color', '#06b6d4'),
            'accent_color' => $request->input('theme_accent_color', '#a855f7'),
            'btn_grad_start' => $request->input('theme_btn_grad_start', '#a855f7'),
            'btn_grad_end' => $request->input('theme_btn_grad_end', '#6366f1'),
            'bg_dark' => $request->input('theme_bg_dark', '#030712'),
            'bg_dark_2' => $request->input('theme_bg_dark_2', '#050b18'),
            'border_dark' => $request->input('theme_border_dark', 'rgba(255,255,255,0.07)'),
            'surface_dark' => $request->input('theme_surface_dark', '#0d1117'),
            'surface_dark_2' => $request->input('theme_surface_dark_2', '#161b22'),
            'surface_dark_3' => $request->input('theme_surface_dark_3', '#21262d'),
            'text_dark' => $request->input('theme_text_dark', '#f0f6fc'),
            'text_dark_2' => $request->input('theme_text_dark_2', '#8b949e'),
            'text_dark_3' => $request->input('theme_text_dark_3', '#6e7681'),
 
            'primary_color_light' => $request->input('theme_primary_color_light', '#6366f1'),
            'secondary_color_light' => $request->input('theme_secondary_color_light', '#06b6d4'),
            'accent_color_light' => $request->input('theme_accent_color_light', '#a855f7'),
            'btn_grad_start_light' => $request->input('theme_btn_grad_start_light', '#a855f7'),
            'btn_grad_end_light' => $request->input('theme_btn_grad_end_light', '#6366f1'),
            'bg_light' => $request->input('theme_bg_light', '#f4f6fb'),
            'bg_light_2' => $request->input('theme_bg_light_2', '#eef1f8'),
            'border_light' => $request->input('theme_border_light', 'rgba(99,102,241,0.10)'),
            'surface_light' => $request->input('theme_surface_light', '#ffffff'),
            'surface_light_2' => $request->input('theme_surface_light_2', '#f8fafd'),
            'surface_light_3' => $request->input('theme_surface_light_3', '#e5e9f5'),
            'text_light' => $request->input('theme_text_light', '#0e1120'),
            'text_light_2' => $request->input('theme_text_light_2', '#3d4669'),
            'text_light_3' => $request->input('theme_text_light_3', '#8b95b8'),

            'font_family' => $request->input('theme_font_family', 'Outfit'),
            'button_style' => $request->input('theme_button_style', 'rounded-full'),
            'default_mode' => $request->input('theme_default_mode', 'dark'),
            'navbar_cta_text' => [
                'en' => $request->input('navbar_cta_text_en'),
                'bn' => $request->input('navbar_cta_text_bn')],
            'navbar_cta_url' => $request->input('navbar_cta_url', '#contact')
        ];

        // 4. SEO Config
        $settings->seo_defaults = [
            'meta_title' => [
                'en' => $request->input('seo_title_en'),
                'bn' => $request->input('seo_title_bn')],
            'meta_description' => [
                'en' => $request->input('seo_desc_en'),
                'bn' => $request->input('seo_desc_bn')],
            'og_title' => [
                'en' => $request->input('seo_og_title_en'),
                'bn' => $request->input('seo_og_title_bn')],
            'og_description' => [
                'en' => $request->input('seo_og_desc_en'),
                'bn' => $request->input('seo_og_desc_bn')],
            'schema_markup' => $request->input('seo_schema_markup'),
            'keywords' => [
                'en' => $request->input('seo_keywords_en'),
                'bn' => $request->input('seo_keywords_bn')],
            'robots' => $request->input('seo_robots'),
            'canonical_url' => $request->input('seo_canonical'),
            'og_image' => $settings->seo_defaults['og_image'] ?? ''
        ];

        if ($request->hasFile('seo_og_image')) {
            $settings->seo_defaults = array_merge($settings->seo_defaults, [
                'og_image' => \Illuminate\Support\Facades\Storage::disk('public')->url($request->file('seo_og_image')->store('uploads', 'public'))
            ]);
        }

        $settings->google_analytics_id = $request->input('google_analytics_id');
        $settings->google_tag_manager_id = $request->input('google_tag_manager_id');
        $settings->facebook_pixel_id = $request->input('facebook_pixel_id');
        $settings->custom_scripts = $request->input('custom_scripts');
        $settings->footer_scripts = $request->input('footer_scripts');

        $settings->save();

        // 5. Update Homepage Sections Keyed Details
        $sectionsInput = $request->input('sections', []);
        foreach ($sectionsInput as $key => $secData) {
            $section = HomepageSection::where('key', $key)->first();
            if ($section) {
                $section->title = [
                    'en' => $secData['title_en'] ?? '',
                    'bn' => $secData['title_bn'] ?? ''
                ];
                $section->subtitle = [
                    'en' => $secData['subtitle_en'] ?? '',
                    'bn' => $secData['subtitle_bn'] ?? ''
                ];
                $section->description = [
                    'en' => $secData['desc_en'] ?? '',
                    'bn' => $secData['desc_bn'] ?? ''
                ];
                $section->short_description = [
                    'en' => $secData['short_desc_en'] ?? '',
                    'bn' => $secData['short_desc_bn'] ?? ''
                ];
                $section->button_text = [
                    'en' => $secData['btn_text_en'] ?? '',
                    'bn' => $secData['btn_text_bn'] ?? ''
                ];
                $section->button_url = $secData['btn_url'] ?? '';
                $section->icon = $secData['icon'] ?? '';
                // Preserve existing order when the settings form doesn't submit it
                if (isset($secData['sort_order']) && $secData['sort_order'] !== '') {
                    $section->sort_order = intval($secData['sort_order']);
                }
                $section->visible = isset($secData['visible']);

                // File Uploads per section
                if ($request->hasFile("sections.{$key}.background_image")) {
                    $section->background_image = \Illuminate\Support\Facades\Storage::disk('public')->url($request->file("sections.{$key}.background_image")->store('uploads', 'public'));
                } elseif (!empty($secData['background_image_text'])) {
                    $section->background_image = $secData['background_image_text'];
                }

                if ($request->hasFile("sections.{$key}.main_image")) {
                    $section->main_image = \Illuminate\Support\Facades\Storage::disk('public')->url($request->file("sections.{$key}.main_image")->store('uploads', 'public'));
                } elseif (!empty($secData['main_image_text'])) {
                    $section->main_image = $secData['main_image_text'];
                }

                // If cards or statistics are updated (e.g. from JSON textarea blocks)
                if (isset($secData['cards_raw'])) {
                    $section->cards = json_decode($secData['cards_raw'], true) ?: [];
                }
                if (isset($secData['statistics_raw'])) {
                    $section->statistics = json_decode($secData['statistics_raw'], true) ?: [];
                }
                if (isset($secData['colors_raw'])) {
                    $section->colors = json_decode($secData['colors_raw'], true) ?: [];
                }

                $section->save();
            }
        }

        return redirect()->back()->with('success', 'CMS settings and homepage sections updated successfully.');
    }
}

