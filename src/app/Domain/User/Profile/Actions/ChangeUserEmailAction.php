<?php

declare(strict_types=1);

namespace App\Domain\User\Profile\Actions;

use App\Domain\User\Profile\DTO\ChangeUserEmailDTO;
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

    public function run(User $user, ChangeUserEmailDTO $dto): void
    {
        $this->userRepository->setPendingEmail($user, $dto->getEmail());
        $this->notificationService->sendEmailChangeVerificationNotification($user, $dto->getEmail());
    }
}
