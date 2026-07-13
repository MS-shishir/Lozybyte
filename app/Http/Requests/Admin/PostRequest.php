<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
        $postId = $this->route('post') ? $this->route('post')->id : null;
        
        return [
            'slug' => 'required|string|max:255|unique:posts,slug,' . $postId,
            'category_id' => 'required|exists:categories,id',
            'title_en' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',

            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'content_en' => 'nullable|string',
            'content_bn' => 'nullable|string',

            'seo_title_en' => 'nullable|string|max:255',
            'seo_title_bn' => 'nullable|string|max:255',

            'seo_desc_en' => 'nullable|string',
            'seo_desc_bn' => 'nullable|string',

        ];
    }
}
