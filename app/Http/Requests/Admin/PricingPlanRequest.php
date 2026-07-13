<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PricingPlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name_en' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',

            'description_en' => 'nullable|string',
            'description_bn' => 'nullable|string',

            'price' => 'nullable|string|max:255',
            'billing_cycle' => 'nullable|string|max:255',
            'features_en' => 'nullable|string',
            'features_bn' => 'nullable|string',

            'sort_order' => 'nullable|integer'
        ];
    }
}
