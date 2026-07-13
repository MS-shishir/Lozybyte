<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class Setting extends Model
{
    use HasTranslations;

    protected $fillable = [
        'website_name',
        'company_name',
        'tagline',
        'company_description',
        'logo_path',
        'dark_logo_path',
        'mobile_logo_path',
        'favicon_path',
        'footer_logo_path',
        'email',
        'phone',
        'whatsapp',
        'address',
        'google_map_embed',
        'business_hours',
        'social_links',
        'theme_config',
        'seo_defaults',
        'homepage_sections',
        'google_analytics_id',
        'google_tag_manager_id',
        'facebook_pixel_id',
        'custom_scripts',
        'footer_scripts'
    ];

    protected $translatable = [
        'website_name',
        'company_name',
        'tagline',
        'company_description',
        'address',
        'business_hours'
    ];

    protected $casts = [
        'website_name' => 'array',
        'company_name' => 'array',
        'tagline' => 'array',
        'company_description' => 'array',
        'address' => 'array',
        'business_hours' => 'array',
        'social_links' => 'array',
        'theme_config' => 'array',
        'seo_defaults' => 'array',
        'homepage_sections' => 'array'
    ];
}
