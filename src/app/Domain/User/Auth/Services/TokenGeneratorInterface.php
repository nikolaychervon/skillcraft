<?php

declare(strict_types=1);

namespace App\Domain\User\Auth\Services;

interface TokenGeneratorInterface
{
    public function generate(int $length): string;
}
