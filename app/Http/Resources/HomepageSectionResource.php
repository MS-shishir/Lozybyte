<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomepageSectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = $request->header('Accept-Language', $request->query('lang', 'en'));
        if (!in_array($lang, ['en', 'bn'])) $lang = 'en';

        $t = fn($field) => is_array($field) ? ($field[$lang] ?? $field['en'] ?? '') : ($field ?? '');

        return [
            'id'                => $this->id,
            'key'               => $this->key,
            'title'             => $t($this->title),
            'subtitle'          => $t($this->subtitle),
            'description'       => $t($this->description),
            'short_description' => $t($this->short_description),
            'button_text'       => $t($this->button_text),
            'button_url'        => $this->button_url,
            'background_image'  => $this->background_image,
            'main_image'        => $this->main_image,
            'icon'              => $this->icon,
            'cards'             => $this->cards ?? [],
            'statistics'        => $this->statistics ?? [],
            'colors'            => $this->colors ?? [],
            'sort_order'        => $this->sort_order,
            'visible'           => $this->visible,
        ];
    }
}