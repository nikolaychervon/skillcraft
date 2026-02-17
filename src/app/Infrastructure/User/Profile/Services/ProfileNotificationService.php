<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Profile\Services;

use App\Domain\User\Profile\Services\ProfileNotificationServiceInterface;
use App\Infrastructure\Notifications\Profile\VerifyEmailChangeNotification;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

class ProfileNotificationService implements ProfileNotificationServiceInterface
{
    public function sendEmailChangeVerificationNotification(User $user, string $pendingEmail): void
    {
        Notification::route('mail', $pendingEmail)->notify(
            new VerifyEmailChangeNotification(
                userId: (int) $user->getKey(),
                name: $user->first_name,
                pendingEmail: $pendingEmail,
            )
        );
    }
}
