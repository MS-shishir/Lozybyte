<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndustryResource extends JsonResource
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

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'icon' => $this->icon ?? 'Building2',
            'sort_order' => (int)($this->sort_order ?? 0),
            'status' => (bool)$this->status,
            'title' => $t($this->title),
            'description' => $t($this->description)
        ];
    }
}
