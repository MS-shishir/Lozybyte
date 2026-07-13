<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NavItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = $request->header('Accept-Language', $request->query('lang', 'en'));
        if (!in_array($lang, ['en', 'bn'])) $lang = 'en';

        return [
            'id' => $this->id,
            'url' => $this->url,
            'icon' => $this->icon,
            'order' => $this->order,
            'status' => $this->status,
            'parent_id' => $this->parent_id,
            'label' => is_array($this->label) ? ($this->label[$lang] ?? $this->label['en'] ?? '') : ($this->label ?? ''),
            'children' => $this->whenLoaded('children', fn() => self::collection($this->children)),
        ];
    }
}