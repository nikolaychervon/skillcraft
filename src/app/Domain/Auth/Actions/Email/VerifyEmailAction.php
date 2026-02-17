<?php

declare(strict_types=1);

namespace App\Domain\Auth\Actions\Email;

use App\Application\Shared\Exceptions\User\Email\EmailAlreadyVerifiedException;
use App\Application\Shared\Exceptions\User\Email\InvalidConfirmationLinkException;
use App\Application\Shared\Exceptions\User\UserNotFoundException;
use App\Domain\Auth\Constants\AuthConstants;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Domain\Auth\Services\TokenServiceInterface;
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
