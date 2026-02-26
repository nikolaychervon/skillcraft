<?php

declare(strict_types=1);

namespace App\Domain\User\Auth\Actions;

use App\Domain\User\Auth\Constants\AuthConstants;
use App\Domain\User\Auth\RequestData\LoginUserRequestData;
use App\Domain\User\Auth\Exceptions\IncorrectLoginDataException;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\Auth\Services\HashServiceInterface;
use App\Domain\User\Auth\Services\TokenServiceInterface;
use App\Domain\User\Auth\Specifications\UserNotConfirmedSpecification;

class LoginUserAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly HashServiceInterface $hashService,
        private readonly TokenServiceInterface $tokenService,
        private readonly UserNotConfirmedSpecification $userNotConfirmedSpecification
    ) {
    }

    /**
     * @throws IncorrectLoginDataException
     */
    public function run(LoginUserRequestData $userRequestData): string
    {
        $user = $this->userRepository->findByEmail($userRequestData->email);

        if ($this->userNotConfirmedSpecification->isSatisfiedBy($user)) {
            throw new IncorrectLoginDataException();
        }

        if (!$this->hashService->check($userRequestData->password, $user->password)) {
            throw new IncorrectLoginDataException();
        }

        return $this->tokenService->createAuthToken($user, AuthConstants::DEFAULT_TOKEN_NAME);
    }
}
