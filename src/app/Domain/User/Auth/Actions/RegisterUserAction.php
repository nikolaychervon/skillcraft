<?php

declare(strict_types=1);

namespace App\Domain\User\Auth\Actions;

use App\Domain\User\Auth\RequestData\CreatingUserRequestData;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\Auth\Services\NotificationServiceInterface;
use App\Models\User;

class RegisterUserAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly CreateNewUserAction $createNewUserAction,
        private readonly NotificationServiceInterface $notificationService
    ) {
    }

    public function run(CreatingUserRequestData $creatingUserRequestData): User
    {
        $user = $this->userRepository->findByEmail($creatingUserRequestData->email);

        if (!$user instanceof User) {
            $user = $this->createNewUserAction->run($creatingUserRequestData);
        }

        $this->notificationService->sendEmailVerificationNotification($user);

        return $user;
    }
}
