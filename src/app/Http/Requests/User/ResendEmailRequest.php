<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Traits\HasEmail;
use Illuminate\Foundation\Http\FormRequest;

class ResendEmailRequest extends FormRequest
{
    use HasEmail;

    public function rules(): array
    {
        return [
            'email' => 'required|email|max:255',
        ];
    }
}
