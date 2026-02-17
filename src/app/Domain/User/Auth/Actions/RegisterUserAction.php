<?php

declare(strict_types=1);

namespace App\Domain\User\Auth\Actions;

use App\Domain\User\Auth\DTO\CreatingUserDTO;
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

    public function run(CreatingUserDTO $creatingUserDTO): User
    {
        $user = $this->userRepository->findByEmail($creatingUserDTO->getEmail());

        if (!$user instanceof User) {
            $user = $this->createNewUserAction->run($creatingUserDTO);
        }

        $this->notificationService->sendEmailVerificationNotification($user);

        return $user;
    }
}
