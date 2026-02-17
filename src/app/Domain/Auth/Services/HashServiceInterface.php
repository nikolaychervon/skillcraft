<?php

declare(strict_types=1);

namespace App\Domain\Auth\Services;

interface HashServiceInterface
{
    /**
     * Хеширует пароль
     *
     * @param string $password
     * @return string
     */
    public function make(string $password): string;

    /**
     * Проверяет соответствие пароля хешу
     *
     * @param string $password
     * @param string $hashedPassword
     * @return bool
     */
    public function check(string $password, string $hashedPassword): bool;
}
