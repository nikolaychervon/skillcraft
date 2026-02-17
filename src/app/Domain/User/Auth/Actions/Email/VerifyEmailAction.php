<?php

declare(strict_types=1);

namespace App\Domain\User\Auth\Actions\Email;

use App\Domain\User\Exceptions\Email\EmailAlreadyVerifiedException;
use App\Domain\User\Exceptions\Email\InvalidConfirmationLinkException;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Auth\Constants\AuthConstants;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\Auth\Services\TokenServiceInterface;
use App\Models\User;

class VerifyEmailAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly TokenServiceInterface $tokenService
    ) {
    }

    /**
     * @throws EmailAlreadyVerifiedException
     * @throws InvalidConfirmationLinkException
     * @throws UserNotFoundException
     */
    public function run(int $id, string $hash): string
    {
        $user = $this->userRepository->findById($id);
        if (!$user instanceof User) {
            throw new UserNotFoundException(['id' => $id]);
        }

        if ($user->hasVerifiedEmail()) {
            throw new EmailAlreadyVerifiedException();
        }

        if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
            throw new InvalidConfirmationLinkException();
        }

        $user->markEmailAsVerified();

        return $this->tokenService->createAuthToken($user, AuthConstants::DEFAULT_TOKEN_NAME);
    }
}
