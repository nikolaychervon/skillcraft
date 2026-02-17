<?php

declare(strict_types=1);

namespace App\Domain\Auth\Actions;

use App\Domain\Auth\DTO\CreatingUserDTO;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Domain\Auth\Services\NotificationServiceInterface;
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
