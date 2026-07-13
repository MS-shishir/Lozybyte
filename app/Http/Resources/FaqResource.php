<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FaqResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = $request->header('Accept-Language', $request->query('lang', 'en'));
        if (!in_array($lang, ['en', 'bn'])) {
            $lang = 'en';
        }

        return [
            'id'         => $this->id,
            'question'   => $this->question[$lang] ?? $this->question['en'] ?? '',
            'answer'     => $this->answer[$lang]   ?? $this->answer['en']   ?? '',
            'sort_order' => $this->sort_order,
            'status'     => $this->status,
        ];
    }
}
