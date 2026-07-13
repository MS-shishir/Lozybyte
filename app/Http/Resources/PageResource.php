<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\HtmlSanitizer;

class PageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = $request->header('Accept-Language', $request->query('lang', 'en'));
        if (!in_array($lang, ['en', 'bn'])) $lang = 'en';

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'banner_image' => $this->banner_image,
            'status' => $this->status,
            'seo' => $this->seo,
            'title' => is_array($this->title) ? ($this->title[$lang] ?? $this->title['en'] ?? '') : ($this->title ?? ''),
            'body' => HtmlSanitizer::clean(
                is_array($this->body) ? ($this->body[$lang] ?? $this->body['en'] ?? '') : ($this->body ?? '')
            ),
        ];
    }
}