<?php

declare(strict_types=1);

namespace App\Domain\Auth\Services;

use App\Models\User;

interface TokenServiceInterface
{
    /**
     * Создает токен аутентификации для пользователя
     *
     * @param User $user
     * @param string $tokenName
     * @return string
     */
    public function createAuthToken(User $user, string $tokenName = 'auth_token'): string;

    /**
     * Удаляет текущий токен пользователя
     *
     * @param User $user
     * @return void
     */
    public function deleteCurrentToken(User $user): void;

    /**
     * Удаляет все токены пользователя
     *
     * @param User $user
     * @return void
     */
    public function deleteAllTokens(User $user): void;
}
