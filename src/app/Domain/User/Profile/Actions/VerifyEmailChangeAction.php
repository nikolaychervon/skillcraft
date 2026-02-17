<?php

declare(strict_types=1);

namespace App\Domain\User\Profile\Actions;

use App\Domain\User\Exceptions\Email\InvalidConfirmationLinkException;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Models\User;

class VerifyEmailChangeAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * @throws InvalidConfirmationLinkException
     * @throws UserNotFoundException
     */
    public function run(int $id, string $hash): void
    {
        $user = $this->userRepository->findById($id);
        if (!$user instanceof User) {
            throw new UserNotFoundException(['id' => $id]);
        }

        if (!$user->pending_email) {
            throw new InvalidConfirmationLinkException();
        }

        if (!hash_equals($hash, sha1($user->pending_email))) {
            throw new InvalidConfirmationLinkException();
        }

        $this->userRepository->confirmPendingEmail($user);
        $user->refresh();
    }
}
