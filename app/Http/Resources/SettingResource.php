<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = $request->header('Accept-Language', $request->query('lang', 'en'));
        if (!in_array($lang, ['en', 'bn'])) $lang = 'en';

        $t = fn($field) => (is_array($field) ? ($field[$lang] ?? $field['en'] ?? '') : ($field ?? ''));

        return [
            'company_name'        => $t($this->company_name),
            'website_name'        => $t($this->website_name),
            'tagline'             => $t($this->tagline),
            'company_description' => $t($this->company_description),
            'address'             => $t($this->address),
            'business_hours'      => $t($this->business_hours),
            'logo_path'           => $this->logo_path,
            'dark_logo_path'      => $this->dark_logo_path,
            'mobile_logo_path'    => $this->mobile_logo_path,
            'favicon_path'        => $this->favicon_path,
            'footer_logo_path'    => $this->footer_logo_path,
            'email'               => $this->email,
            'phone'               => $this->phone,
            'whatsapp'            => $this->whatsapp,
            'google_map_embed'    => $this->google_map_embed,
            'social_links'        => $this->social_links,
            'theme_config'        => $this->theme_config,
            'seo_defaults'        => $this->seo_defaults,
            'homepage_sections'   => $this->homepage_sections,
            'google_analytics_id' => $this->google_analytics_id,
            'google_tag_manager_id' => $this->google_tag_manager_id,
            'facebook_pixel_id'   => $this->facebook_pixel_id,
            'custom_scripts'      => $this->custom_scripts,
            'footer_scripts'      => $this->footer_scripts,
        ];
    }
}