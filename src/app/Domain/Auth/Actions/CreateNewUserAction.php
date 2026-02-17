<?php

declare(strict_types=1);

namespace App\Domain\Auth\Actions;

use App\Domain\Auth\DTO\CreatingUserDTO;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Domain\Auth\Services\HashServiceInterface;
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
