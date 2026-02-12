<?php

namespace App\Http\Requests\Traits;

trait HasPassword
{
    public function getUserPassword(): string
    {
        return $this->input('password');
    }
}
