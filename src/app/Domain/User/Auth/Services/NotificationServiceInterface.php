<?php

declare(strict_types=1);

namespace App\Domain\User\Auth\Services;

use App\Models\User;

interface NotificationServiceInterface
{
    public function sendEmailVerificationNotification(User $user): void;

    public function sendPasswordResetNotification(User $user, string $email, string $resetToken): void;
}
