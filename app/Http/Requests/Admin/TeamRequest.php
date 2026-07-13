<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('team')?->user_id;

        return [
            'name'           => 'required|string|max:255',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'role_en'        => 'nullable|string|max:255',
            'role_bn'        => 'nullable|string|max:255',
            'social_linkedin'=> 'nullable|url|max:255',
            'social_github'  => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            // Admin account fields
            'email'          => 'nullable|email|max:255|unique:users,email,' . ($userId ?? 'NULL'),
            'password'       => 'nullable|string|min:8',
            'system_role'    => 'nullable|in:super_admin,content_manager,sales_manager,saas_manager',
        ];
    }
}
