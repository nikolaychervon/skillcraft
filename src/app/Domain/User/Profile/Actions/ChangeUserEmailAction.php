<?php

declare(strict_types=1);

namespace App\Domain\User\Profile\Actions;

use App\Domain\User\Profile\RequestData\ChangeUserEmailRequestData;
use App\Domain\User\Profile\Services\ProfileNotificationServiceInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Models\User;

class ChangeUserEmailAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly ProfileNotificationServiceInterface $notificationService
    ) {
    }

    public function run(User $user, ChangeUserEmailRequestData $requestData): void
    {
        $this->userRepository->setPendingEmail($user, $requestData->email);
        $this->notificationService->sendEmailChangeVerificationNotification($user, $requestData->email);
    }
}
