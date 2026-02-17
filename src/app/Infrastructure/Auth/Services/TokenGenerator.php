<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Services;

use App\Domain\Auth\Services\TokenGeneratorInterface;
use Illuminate\Support\Str;

class TokenGenerator implements TokenGeneratorInterface
{
    public function generate(int $length): string
    {
        return Str::random($length);
    }
}
