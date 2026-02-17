<?php

declare(strict_types=1);

namespace App\Domain\Profile\Services;

use App\Models\User as DeveloperProfile;

interface DeveloperProfileNotificationServiceInterface
{
    /**
     * Отправляет уведомление о верификации email при изменении
     *
     * @param DeveloperProfile $developerProfile
     * @param string $pendingEmail
     * @return void
     */
    public function sendEmailChangeVerificationNotification(DeveloperProfile $developerProfile, string $pendingEmail): void;
}
