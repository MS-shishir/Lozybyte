<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = $request->header('Accept-Language', $request->query('lang', 'en'));
        if (!in_array($lang, ['en', 'bn'])) $lang = 'en';

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'posts_count' => $this->posts_count,
            'name' => is_array($this->name) ? ($this->name[$lang] ?? $this->name['en'] ?? '') : ($this->name ?? ''),
        ];
    }
}