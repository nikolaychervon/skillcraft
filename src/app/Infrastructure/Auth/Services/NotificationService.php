<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Services;

use App\Domain\Auth\Services\NotificationServiceInterface;
use App\Infrastructure\Notifications\Auth\PasswordResetNotification;
use App\Infrastructure\Notifications\Auth\VerifyEmailForRegisterNotification;
use App\Models\User;

class NotificationService implements NotificationServiceInterface
{
    public function sendEmailVerificationNotification(User $user): void
    {
        $user->notify(new VerifyEmailForRegisterNotification());
    }

    public function sendPasswordResetNotification(User $user, string $email, string $resetToken): void
    {
        $user->notify(new PasswordResetNotification($email, $resetToken));
    }
}
