<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = $request->header('Accept-Language', $request->query('lang', 'en'));
        if (!in_array($lang, ['en', 'bn'])) $lang = 'en';

        $t = function ($field) use ($lang) {
            if (is_array($field)) {
                return $field[$lang] ?? $field['en'] ?? '';
            }
            return $field ?? '';
        };

        $tArr = function ($field) use ($lang) {
            if (is_array($field)) {
                if (isset($field['en']) || isset($field['bn'])) {
                    $val = $field[$lang] ?? $field['en'] ?? [];
                } else {
                    return $field;
                }
                if (is_string($val)) {
                    $decoded = json_decode($val, true);
                    return is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $val)));
                }
                return is_array($val) ? $val : [];
            }
            return [];
        };

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'category' => $this->category,
            'pricing' => $this->pricing,
            'demo_url' => $this->demo_url,
            'status' => $this->status,
            'icon' => $this->icon ?? 'Package',
            'color' => $this->color ?? '#6366f1',
            'badge_color' => $this->badge_color ?? $this->color ?? '#6366f1',
            'clients_count' => $this->clients_count ?? 0,
            'rating' => (double)($this->rating ?? 4.8),
            'screenshot_type' => $this->screenshot_type ?? 'school',
            'sort_order' => (int)($this->sort_order ?? 0),
            'title' => $t($this->title),
            'tagline' => $t($this->tagline),
            'badge' => $t($this->badge),
            'description' => $t($this->description),
            'features' => $tArr($this->features),
            'screenshots' => collect($this->screenshots ?? [])->filter(function ($s) {
                // Skip legacy /images/... placeholder paths that don't exist
                if (str_starts_with($s, '/images/')) return false;
                return !empty($s);
            })->map(function ($s) {
                // Normalize to relative /storage/... path
                if (str_starts_with($s, 'http://') || str_starts_with($s, 'https://')) {
                    // Extract relative path from old full URLs like http://localhost:8000/storage/products/abc.webp
                    if (preg_match('#/storage/(.+)$#', $s, $m)) {
                        return '/storage/' . $m[1];
                    }
                    return $s; // External URL, keep as-is
                }
                // Already relative path like "products/abc.webp" → prepend /storage/
                if (!str_starts_with($s, '/')) {
                    return '/storage/' . $s;
                }
                return $s; // Already starts with /
            })->values()->toArray(),
        ];
    }
}