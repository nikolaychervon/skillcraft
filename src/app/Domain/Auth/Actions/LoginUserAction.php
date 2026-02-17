<?php

declare(strict_types=1);

namespace App\Domain\Auth\Actions;

use App\Domain\Auth\Constants\AuthConstants;
use App\Domain\Auth\DTO\LoginUserDTO;
use App\Domain\Auth\Exceptions\IncorrectLoginDataException;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Domain\Auth\Services\HashServiceInterface;
use App\Domain\Auth\Services\TokenServiceInterface;
use App\Domain\Auth\Specifications\UserNotConfirmedSpecification;

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
    public function run(LoginUserDTO $userDTO): string
    {
        $user = $this->userRepository->findByEmail($userDTO->getEmail());

        if ($this->userNotConfirmedSpecification->isSatisfiedBy($user)) {
            throw new IncorrectLoginDataException();
        }

        if (!$this->hashService->check($userDTO->getPassword(), $user->password)) {
            throw new IncorrectLoginDataException();
        }

        return $this->tokenService->createAuthToken($user, AuthConstants::DEFAULT_TOKEN_NAME);
    }
}
