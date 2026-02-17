<?php

declare(strict_types=1);

namespace App\Domain\User\Auth\Services;

interface HashServiceInterface
{
    /** Хеширует пароль. */
    public function make(string $password): string;

    /** Проверяет пароль против хеша. */
    public function check(string $password, string $hashedPassword): bool;
}
