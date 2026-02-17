<?php

declare(strict_types=1);

namespace App\Domain\User\Auth\Cache;

interface PasswordResetTokensCacheInterface
{
    public function store(string $email, string $token): void;

    public function get(string $email): ?string;

    public function delete(string $email): void;
}
