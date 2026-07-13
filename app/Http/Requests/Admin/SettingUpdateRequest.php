<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SettingUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Company Details
            'website_name_en' => 'nullable|string|max:255',
            'website_name_bn' => 'nullable|string|max:255',

            'company_name_en' => 'required|string|max:255',
            'company_name_bn' => 'required|string|max:255',

            'tagline_en' => 'required|string|max:255',
            'tagline_bn' => 'required|string|max:255',

            'company_desc_en' => 'nullable|string',
            'company_desc_bn' => 'nullable|string',


            // Contact Info
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'address_en' => 'nullable|string',
            'address_bn' => 'nullable|string',

            'google_map_embed' => 'nullable|string',
            'business_hours_en' => 'nullable|string',
            'business_hours_bn' => 'nullable|string',


            // Media Files
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'dark_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'mobile_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png,svg|max:1024',
            'footer_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',

            // Social Links
            'social_facebook' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_linkedin' => 'nullable|url',
            'social_github' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'social_youtube' => 'nullable|url',
            'social_tiktok' => 'nullable|url',
            'social_telegram' => 'nullable|url',
            'social_messenger' => 'nullable|url',

            // Theme Config
            'theme_primary_color' => 'nullable|string|max:50',
            'theme_secondary_color' => 'nullable|string|max:50',
            'theme_accent_color' => 'nullable|string|max:50',
            'theme_bg_dark' => 'nullable|string|max:50',
            'theme_bg_dark_2' => 'nullable|string|max:50',
            'theme_border_dark' => 'nullable|string|max:50',
            'theme_surface_dark' => 'nullable|string|max:50',
            'theme_surface_dark_2' => 'nullable|string|max:50',
            'theme_surface_dark_3' => 'nullable|string|max:50',
            'theme_text_dark' => 'nullable|string|max:50',
            'theme_text_dark_2' => 'nullable|string|max:50',
            'theme_text_dark_3' => 'nullable|string|max:50',

            'theme_primary_color_light' => 'nullable|string|max:50',
            'theme_secondary_color_light' => 'nullable|string|max:50',
            'theme_accent_color_light' => 'nullable|string|max:50',
            'theme_bg_light' => 'nullable|string|max:50',
            'theme_bg_light_2' => 'nullable|string|max:50',
            'theme_border_light' => 'nullable|string|max:50',
            'theme_surface_light' => 'nullable|string|max:50',
            'theme_surface_light_2' => 'nullable|string|max:50',
            'theme_surface_light_3' => 'nullable|string|max:50',
            'theme_text_light' => 'nullable|string|max:50',
            'theme_text_light_2' => 'nullable|string|max:50',
            'theme_text_light_3' => 'nullable|string|max:50',

            'theme_font_family' => 'nullable|string|max:100',
            'theme_button_style' => 'nullable|string|max:50',
            'theme_default_mode' => 'nullable|in:light,dark',
            'navbar_cta_text_en' => 'nullable|string|max:100',
            'navbar_cta_text_bn' => 'nullable|string|max:100',
            'navbar_cta_url' => 'nullable|string|max:255',

            // SEO Config
            'seo_title_en' => 'nullable|string|max:255',
            'seo_desc_en' => 'nullable|string',
            'seo_keywords_en' => 'nullable|string',
            'seo_robots' => 'nullable|string|max:255',
            'seo_canonical' => 'nullable|url',
            'seo_og_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',

            // Analytics
            'google_analytics_id' => 'nullable|string|max:100',
            'google_tag_manager_id' => 'nullable|string|max:100',
            'facebook_pixel_id' => 'nullable|string|max:100',
            'custom_scripts' => 'nullable|string',
            'footer_scripts' => 'nullable|string',
        ];
    }
}
