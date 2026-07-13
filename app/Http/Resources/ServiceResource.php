<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = $request->header('Accept-Language', $request->query('lang', 'en'));
        if (!in_array($lang, ['en', 'bn'])) $lang = 'en';

        // Helper: translate a field (array or string)
        $t = function ($field) use ($lang) {
            if (is_array($field)) {
                return $field[$lang] ?? $field['en'] ?? '';
            }
            return $field ?? '';
        };

        // Helper: translate array of strings (features / process_steps)
        $tArr = function ($field) use ($lang) {
            if (is_array($field)) {
                $val = $field[$lang] ?? $field['en'] ?? [];
                // could be stored as a JSON string inside the array
                if (is_string($val)) {
                    $decoded = json_decode($val, true);
                    return is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $val)));
                }
                return is_array($val) ? $val : [];
            }
            return [];
        };

        return [
            'id'             => $this->id,
            'slug'           => $this->slug,
            'icon'           => $this->icon,
            'color'          => $this->color ?? '#6366f1',
            'glow_color'     => $this->glow_color ?? 'rgba(99,102,241,0.2)',
            'image_path'     => $this->image_path,
            'status'         => $this->status,
            'sort_order'     => $this->sort_order ?? 0,
            'title'          => $t($this->title),
            'description'    => $t($this->description),
            'details'        => $t($this->details),
            'timeline'       => $this->timeline,
            'starting_price' => $this->starting_price,
            'case_result'    => $t($this->case_result),
            'features'       => $tArr($this->features),
            'process_steps'  => $tArr($this->process_steps),
        ];
    }
}