<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\HtmlSanitizer;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = $request->header('Accept-Language', $request->query('lang', 'en'));
        if (!in_array($lang, ['en', 'bn'])) $lang = 'en';

        $categoryName = $this->category && $this->category->name
            ? (is_array($this->category->name)
                ? ($this->category->name[$lang] ?? $this->category->name['en'] ?? '')
                : $this->category->name)
            : null;

        $isSingle = $request->route() && str_contains($request->route()->uri(), '{slug}');
        $rawContent = is_array($this->content) ? ($this->content[$lang] ?? $this->content['en'] ?? '') : ($this->content ?? '');

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'category_id' => $this->category_id,
            'category' => $this->category ? [
                'id' => $this->category->id,
                'slug' => $this->category->slug,
                'name' => $categoryName,
            ] : null,
            'author' => $this->author,
            'image_path' => $this->image_path,
            'banner_image' => $this->image_path,
            'reading_time' => $this->reading_time,
            'status' => $this->status,
            'published_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
            'title' => is_array($this->title) ? ($this->title[$lang] ?? $this->title['en'] ?? '') : ($this->title ?? ''),
            'excerpt' => \Illuminate\Support\Str::limit(strip_tags($rawContent), 180),
            'body' => $isSingle ? HtmlSanitizer::clean($rawContent) : null,
            'content' => $isSingle ? HtmlSanitizer::clean($rawContent) : null,
        ];
    }
}