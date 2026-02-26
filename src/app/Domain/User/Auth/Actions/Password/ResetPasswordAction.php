<?php

declare(strict_types=1);

namespace App\Domain\User\Auth\Actions\Password;

use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Auth\Constants\AuthConstants;
use App\Domain\User\Auth\Cache\PasswordResetTokensCacheInterface;
use App\Domain\User\Auth\RequestData\ResetPasswordRequestData;
use App\Domain\User\Auth\Exceptions\InvalidResetTokenException;
use App\Domain\User\Auth\Exceptions\PasswordResetFailedException;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\Auth\Services\HashServiceInterface;
use App\Domain\User\Auth\Services\TokenServiceInterface;
use App\Domain\User\Auth\Services\TransactionServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ResetPasswordAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly PasswordResetTokensCacheInterface $passwordResetTokensCache,
        private readonly HashServiceInterface $hashService,
        private readonly TokenServiceInterface $tokenService,
        private readonly TransactionServiceInterface $transactionService
    ) {
    }

    /**
     * @throws InvalidResetTokenException
     * @throws PasswordResetFailedException
     * @throws UserNotFoundException
     */
    public function run(ResetPasswordRequestData $resetPasswordRequestData): string
    {
        $token = $this->passwordResetTokensCache->get($resetPasswordRequestData->getEmail());
        if (!$token || $token !== $resetPasswordRequestData->getResetToken()) {
            throw new InvalidResetTokenException();
        }

        $user = $this->userRepository->findByEmail($resetPasswordRequestData->getEmail());
        if (!$user instanceof User) {
            throw new UserNotFoundException(['email' => $resetPasswordRequestData->getEmail()]);
        }

        return $this->reset($user, $resetPasswordRequestData);
    }

    /**
     * @throws PasswordResetFailedException
     */
    private function reset(User $user, ResetPasswordRequestData $resetPasswordRequestData): string
    {
        try {
            return $this->transactionService->transaction(function () use ($user, $resetPasswordRequestData) {
                $hashedPassword = $this->hashService->make($resetPasswordRequestData->getPassword());
                $this->userRepository->updatePassword($user, $hashedPassword);

                $this->passwordResetTokensCache->delete($resetPasswordRequestData->getEmail());
                $this->tokenService->deleteAllTokens($user);

                return $this->tokenService->createAuthToken($user, AuthConstants::DEFAULT_TOKEN_NAME);
            });
        } catch (\Throwable $e) {
            Log::error('Password reset failed', [
                'email' => $resetPasswordRequestData->getEmail(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw new PasswordResetFailedException(previous: $e);
        }
    }
}
