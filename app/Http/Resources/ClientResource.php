<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'logo' => $this->logo_path
                ? (preg_match('#^(https?://|/)#', $this->logo_path)
                    ? $this->logo_path
                    : \Illuminate\Support\Facades\Storage::disk('public')->url($this->logo_path))
                : null,
            'url' => $this->url,
            'sort_order' => $this->sort_order,
        ];
    }
}
