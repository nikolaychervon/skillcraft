<?php

namespace App\Http\Requests\Traits;

trait HasEmail
{
    public function getEmail(): string
    {
        return $this->input('email');
    }
}
