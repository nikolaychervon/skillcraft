<?php

declare(strict_types=1);

namespace App\Http\Requests\Traits;

trait HasEmail
{
    public function getEmail(): string
    {
        return $this->input('email');
    }
}
