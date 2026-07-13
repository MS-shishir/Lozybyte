<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class IndustryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $industryId = $this->route('industry') ? $this->route('industry')->id : null;

        return [
            'slug' => 'required|string|max:255|unique:industries,slug,' . $industryId,
            'icon' => 'nullable|string|max:255',
            'title_en' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'desc_en' => 'required|string',
            'desc_bn' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }
}
