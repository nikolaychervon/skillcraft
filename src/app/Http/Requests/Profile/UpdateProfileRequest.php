<?php

declare(strict_types=1);

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->has('middle_name') && trim((string) $this->input('middle_name')) === '') {
            $this->merge(['middle_name' => null]);
        }
    }

    public function rules(): array
    {
        $userId = $this->user()?->id;

        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'unique_nickname' => [
                'required',
                'min:3',
                'max:20',
                'regex:/^[a-zA-Z0-9_-]+$/',
                Rule::unique('users', 'unique_nickname')->ignore($userId),
            ],
        ];
    }
}
