<?php

declare(strict_types=1);

namespace App\Domain\User\Profile\Actions;

use App\Domain\User\Profile\RequestData\UpdateUserProfileRequestData;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Models\User;

class UpdateUserProfileAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function run(User $user, UpdateUserProfileRequestData $requestData): User
    {
        return $this->userRepository->update($user, [
            'first_name' => $requestData->firstName,
            'last_name' => $requestData->lastName,
            'middle_name' => $requestData->middleName,
            'unique_nickname' => $requestData->uniqueNickname,
        ]);
    }
}
