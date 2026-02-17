<?php

declare(strict_types=1);

namespace App\Domain\Auth\Services;

use App\Models\User;

interface NotificationServiceInterface
{
    /**
     * Отправляет уведомление о верификации email при регистрации
     *
     * @param User $user
     * @return void
     */
    public function sendEmailVerificationNotification(User $user): void;

    /**
     * Отправляет уведомление о сбросе пароля
     *
     * @param User $user
     * @param string $email
     * @param string $resetToken
     * @return void
     */
    public function sendPasswordResetNotification(User $user, string $email, string $resetToken): void;
}
