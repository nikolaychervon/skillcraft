<?php

namespace App\Http\Requests\User\Auth;

use App\Http\Requests\Traits\HasEmail;
use App\Http\Requests\Traits\HasPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
{
    use HasEmail, HasPassword;

    public function rules(): array
    {
        return [
            'email' => 'required|email|max:255',
            'reset_token' => 'required|string',
            'password' => ['required', 'confirmed', Password::min(8)->max(30)->numbers()->symbols()],
        ];
    }

    public function getResetToken(): string
    {
        return $this->input('reset_token');
    }
}
