<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        $productId = $this->route('product') ? $this->route('product')->id : null;
        
        return [
            'slug' => 'required|string|max:255|unique:products,slug,' . $productId,
            'title_en' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'demo_url' => 'nullable|string|max:255',
            'screenshots.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'price_monthly' => 'nullable|string',
            'link_monthly' => 'nullable|string',
            'price_yearly' => 'nullable|string',
            'link_yearly' => 'nullable|string',
            'price_lifetime' => 'nullable|string',
            'link_lifetime' => 'nullable|string',
            'features_en' => 'nullable|string',
            'features_bn' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'tagline_en' => 'nullable|string|max:255',
            'tagline_bn' => 'nullable|string|max:255',
            'badge_en' => 'nullable|string|max:255',
            'badge_bn' => 'nullable|string|max:255',
            'badge_color' => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'description_bn' => 'nullable|string',
            'clients_count' => 'nullable|integer|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'screenshot_type' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }
}
