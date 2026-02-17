<?php

declare(strict_types=1);

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ChangePasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'old_password' => 'required|string',
            'password' => ['required', 'confirmed', Password::min(8)->max(30)->numbers()->symbols()],
        ];
    }
}
