<?php

declare(strict_types=1);

namespace App\Domain\Profile\Actions;

use App\Domain\Profile\DTO\ChangeDeveloperEmailDTO;
use App\Domain\Profile\Repositories\DeveloperProfileRepositoryInterface;
use App\Domain\Profile\Services\DeveloperProfileNotificationServiceInterface;
use App\Models\User as DeveloperProfile;

class ChangeDeveloperEmailAction
{
    public function __construct(
        private readonly DeveloperProfileRepositoryInterface $developerProfileRepository,
        private readonly DeveloperProfileNotificationServiceInterface $notificationService
    ) {
    }

    public function run(DeveloperProfile $developerProfile, ChangeDeveloperEmailDTO $dto): void
    {
        $this->developerProfileRepository->setPendingEmail($developerProfile, $dto->getEmail());
        $this->notificationService->sendEmailChangeVerificationNotification($developerProfile, $dto->getEmail());
    }
}
