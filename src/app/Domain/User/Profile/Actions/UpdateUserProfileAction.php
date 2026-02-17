<?php

declare(strict_types=1);

namespace App\Domain\User\Profile\Actions;

use App\Domain\User\Profile\DTO\UpdateUserProfileDTO;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Models\User;

class UpdateUserProfileAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function run(User $user, UpdateUserProfileDTO $dto): User
    {
        return $this->userRepository->update($user, [
            'first_name' => $dto->getFirstName(),
            'last_name' => $dto->getLastName(),
            'middle_name' => $dto->getMiddleName(),
            'unique_nickname' => $dto->getUniqueNickname(),
        ]);
    }
}
