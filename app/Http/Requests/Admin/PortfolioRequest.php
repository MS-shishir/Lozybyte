<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PortfolioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $portfolioId = $this->route('portfolio') ? $this->route('portfolio')->id : null;
        
        return [
            'slug' => 'required|string|max:255|unique:portfolios,slug,' . $portfolioId,
            'title_en' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',

            'client' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'industry_en' => 'nullable|string',
            'industry_bn' => 'nullable|string',

            'challenge_en' => 'nullable|string',
            'challenge_bn' => 'nullable|string',

            'solution_en' => 'nullable|string',
            'solution_bn' => 'nullable|string',

            'result_en' => 'nullable|string',
            'result_bn' => 'nullable|string',
            
            // Metadata fields
            'color' => 'nullable|string|max:10',
            'logo_color' => 'nullable|string|max:10',
            'logo_text' => 'nullable|string|max:255',
            'logo_icon' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'team' => 'nullable|string|max:255',
            'launched' => 'nullable|string|max:255',
            'tag_en' => 'nullable|string|max:255',
            'tag_bn' => 'nullable|string|max:255',
            'stats_en' => 'nullable|array',
            'stats_bn' => 'nullable|array',
            'tech' => 'nullable|array',
            'metrics' => 'nullable|array',

        ];
    }
}
