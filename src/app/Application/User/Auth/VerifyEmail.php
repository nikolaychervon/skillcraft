<?php

declare(strict_types=1);

namespace App\Application\User\Auth;

use App\Domain\User\Exceptions\Email\EmailAlreadyVerifiedException;
use App\Domain\User\Exceptions\Email\InvalidConfirmationLinkException;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Auth\Constants\AuthConstants;
use App\Domain\User\Auth\Services\TokenServiceInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Models\User;

/**
 * Подтверждение email по подписанной ссылке (id + hash).
 * Отмечает email подтверждённым и возвращает новый API-токен.
 */
final readonly class VerifyEmail
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private TokenServiceInterface $tokenService,
    ) {}

    /** @throws EmailAlreadyVerifiedException|InvalidConfirmationLinkException|UserNotFoundException */
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
