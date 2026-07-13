<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TestimonialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = $request->header('Accept-Language', $request->query('lang', 'en'));
        if (!in_array($lang, ['en', 'bn'])) $lang = 'en';

        return [
            'id' => $this->id,
            'name' => $this->name,
            'designation' => is_array($this->designation) ? ($this->designation[$lang] ?? $this->designation['en'] ?? '') : ($this->designation ?? ''),
            'company' => $this->company,
            'review' => is_array($this->review) ? ($this->review[$lang] ?? $this->review['en'] ?? '') : ($this->review ?? ''),
            'image_path' => $this->image_path
                ? (preg_match('#^(https?://|/)#', $this->image_path)
                    ? $this->image_path
                    : Storage::disk('public')->url($this->image_path))
                : null,
            'rating' => $this->rating,
            'video_url' => $this->video_url,
            'video_path' => $this->video_path
                ? (preg_match('#^(https?://|/)#', $this->video_path)
                    ? $this->video_path
                    : Storage::disk('public')->url($this->video_path))
                : null,
            'status' => $this->status,
        ];
    }
}