<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PricingPlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = $request->header('Accept-Language', $request->query('lang', 'en'));
        if (!in_array($lang, ['en', 'bn'])) $lang = 'en';

        $t = function ($field) use ($lang) {
            if (is_array($field)) return $field[$lang] ?? $field['en'] ?? '';
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
                    return is_array($decoded) ? $decoded : array_filter(array_map('trim', explode("\n", $val)));
                }
                return is_array($val) ? $val : [];
            }
            return [];
        };

        return [
            'id'             => $this->id,
            'name'           => $t($this->name),
            'category'       => $t($this->category),
            'tagline'        => $t($this->tagline),
            'description'    => $t($this->description),
            'badge'          => $t($this->badge),
            'features'       => $tArr($this->features),
            'color'          => $this->color ?? '#6366f1',
            'is_featured'    => (bool) $this->is_featured,
            'status'         => (bool) $this->status,
            'sort_order'     => (int) $this->sort_order,
            'pricing' => [
                'monthly'  => ['price' => $this->price_monthly ?? '—', 'link' => $this->link_monthly ?? '#'],
                'yearly'   => ['price' => $this->price_yearly  ?? '—', 'link' => $this->link_yearly  ?? '#'],
                'lifetime' => ['price' => $this->price_lifetime ?? '—', 'link' => $this->link_lifetime ?? '#'],
            ],
        ];
    }
}
