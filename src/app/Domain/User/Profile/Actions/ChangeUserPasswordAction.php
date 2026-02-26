<?php

declare(strict_types=1);

namespace App\Domain\User\Profile\Actions;

use App\Domain\User\Auth\Services\HashServiceInterface;
use App\Domain\User\Profile\RequestData\ChangeUserPasswordRequestData;
use App\Domain\User\Profile\Exceptions\IncorrectCurrentPasswordException;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Models\User;

class ChangeUserPasswordAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly HashServiceInterface $hashService
    ) {
    }

    /**
     * @throws IncorrectCurrentPasswordException
     */
    public function run(User $user, ChangeUserPasswordRequestData $requestData): void
    {
        if (!$this->hashService->check($requestData->getOldPassword(), $user->password)) {
            throw new IncorrectCurrentPasswordException();
        }

        $hashedPassword = $this->hashService->make($requestData->getPassword());
        $this->userRepository->updatePassword($user, $hashedPassword);
    }
}
