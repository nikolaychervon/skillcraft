<?php

declare(strict_types=1);

namespace App\Application\User\Profile;

use App\Domain\User\Exceptions\Email\InvalidConfirmationLinkException;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Models\User;

/**
 * Подтверждение смены email по подписанной ссылке: переносит pending_email в email и сбрасывает pending.
 */
final readonly class VerifyEmailChange
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    /** @throws InvalidConfirmationLinkException|UserNotFoundException */
    public function run(int $id, string $hash): void
    {
        $user = $this->userRepository->findById($id);
        if (!$user instanceof User) {
            throw new UserNotFoundException(['id' => $id]);
        }

        if ($user->pending_email === null) {
            throw new InvalidConfirmationLinkException();
        }

        if (!hash_equals($hash, sha1($user->pending_email))) {
            throw new InvalidConfirmationLinkException();
        }

        $this->userRepository->confirmPendingEmail($user);
        $user->refresh();
    }
}
