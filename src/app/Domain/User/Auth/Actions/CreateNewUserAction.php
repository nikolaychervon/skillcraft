<?php

declare(strict_types=1);

namespace App\Domain\User\Auth\Actions;

use App\Domain\User\Auth\DTO\CreatingUserDTO;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\Auth\Services\HashServiceInterface;
use App\Models\User;

class CreateNewUserAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly HashServiceInterface $hashService
    ) {
    }

    public function run(CreatingUserDTO $creatingUserDTO): User
    {
        $hashedPassword = $this->hashService->make($creatingUserDTO->getPassword());
        return $this->userRepository->create($creatingUserDTO, $hashedPassword);
    }
}
