<?php

namespace App\Http\Requests\User\Auth;

use App\Http\Requests\Traits\HasEmail;
use App\Http\Requests\Traits\HasPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class LoginRequest extends FormRequest
{
    use HasEmail, HasPassword;

    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:255',
            'password' => ['required', Password::min(8)->max(30)],
        ];
    }
}
