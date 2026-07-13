<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
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
            'question_en' => 'required|string|max:500',
            'question_bn' => 'nullable|string|max:500',

            'answer_en' => 'required|string',
            'answer_bn' => 'nullable|string',

            'sort_order' => 'nullable|integer'
        ];
    }
}
