<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $serviceId = $this->route('service') ? $this->route('service')->id : null;

        return [
            'slug'           => 'required|string|max:255|unique:services,slug,' . $serviceId,
            'title_en'       => 'required|string|max:255',
            'title_bn'       => 'nullable|string|max:255',
            'icon'           => 'nullable|string|max:255',
            'color'          => 'nullable|string|max:50',
            'glow_color'     => 'nullable|string|max:100',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'desc_en'        => 'nullable|string',
            'desc_bn'        => 'nullable|string',
            'details_en'     => 'nullable|string',
            'details_bn'     => 'nullable|string',
            'timeline'       => 'nullable|string|max:100',
            'starting_price' => 'nullable|string|max:100',
            'case_result_en' => 'nullable|string|max:500',
            'case_result_bn' => 'nullable|string|max:500',
            'features_en'    => 'nullable|string',
            'features_bn'    => 'nullable|string',
            'process_steps_en' => 'nullable|string',
            'process_steps_bn' => 'nullable|string',
            'sort_order'     => 'nullable|integer|min:0',
        ];
    }
}
