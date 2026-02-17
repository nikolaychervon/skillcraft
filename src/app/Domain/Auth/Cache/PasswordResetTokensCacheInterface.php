<?php

declare(strict_types=1);

namespace App\Domain\Auth\Cache;

interface PasswordResetTokensCacheInterface
{
    /**
     * Сохраняет токен для конкретного email
     */
    public function store(string $email, string $token): void;

    /**
     * Получает token пользователя по email или отдает null, если кэш просрочен
     */
    public function get(string $email): ?string;

    /**
     * Удаляет токен по email
     */
    public function delete(string $email): void;
}
