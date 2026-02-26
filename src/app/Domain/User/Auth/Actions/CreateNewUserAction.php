<?php

declare(strict_types=1);

namespace App\Domain\User\Auth\Actions;

use App\Domain\User\Auth\RequestData\CreatingUserRequestData;
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

    public function run(CreatingUserRequestData $creatingUserRequestData): User
    {
        $hashedPassword = $this->hashService->make($creatingUserRequestData->password);
        return $this->userRepository->create([
            'first_name' => $creatingUserRequestData->firstName,
            'last_name' => $creatingUserRequestData->lastName,
            'middle_name' => $creatingUserRequestData->middleName,
            'email' => $creatingUserRequestData->email,
            'password' => $hashedPassword,
            'unique_nickname' => $creatingUserRequestData->uniqueNickname,
        ]);
    }
}
