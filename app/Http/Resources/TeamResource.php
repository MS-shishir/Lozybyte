<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TeamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = $request->header('Accept-Language', $request->query('lang', 'en'));
        if (!in_array($lang, ['en', 'bn'])) $lang = 'en';

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'image_path' => $this->image_path
                ? (preg_match('#^(https?://|/)#', $this->image_path)
                    ? $this->image_path
                    : Storage::disk('public')->url($this->image_path))
                : null,
            'social_links' => $this->social_links,
            'status' => $this->status,
            'role' => is_array($this->role) ? ($this->role[$lang] ?? $this->role['en'] ?? '') : ($this->role ?? ''),
            'bio' => is_array($this->bio) ? ($this->bio[$lang] ?? $this->bio['en'] ?? '') : ($this->bio ?? ''),
        ];
    }
}