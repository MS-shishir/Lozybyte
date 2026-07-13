<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TestimonialRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'rating' => 'nullable|integer|min:1|max:5',
            'video_url' => 'nullable|string|max:500',
            'video' => 'nullable|file|mimes:mp4,webm,mov,avi,mkv|max:51200',
            'designation_en' => 'nullable|string',
            'designation_bn' => 'nullable|string',

            'review_en' => 'nullable|string',
            'review_bn' => 'nullable|string',

        ];
    }
}
