<?php

declare(strict_types=1);

namespace App\Infrastructure\Profile\Services;

use App\Domain\Profile\Services\DeveloperProfileNotificationServiceInterface;
use App\Infrastructure\Notifications\Profile\VerifyEmailChangeNotification;
use App\Models\User as DeveloperProfile;
use Illuminate\Support\Facades\Notification;

class DeveloperProfileNotificationService implements DeveloperProfileNotificationServiceInterface
{
    public function sendEmailChangeVerificationNotification(DeveloperProfile $developerProfile, string $pendingEmail): void
    {
        Notification::route('mail', $pendingEmail)->notify(
            new VerifyEmailChangeNotification(
                developerProfileId: (int)$developerProfile->getKey(),
                name: $developerProfile->first_name,
                pendingEmail: $pendingEmail,
            )
        );
    }
}
