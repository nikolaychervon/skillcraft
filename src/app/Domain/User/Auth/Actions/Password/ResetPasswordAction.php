<?php

declare(strict_types=1);

namespace App\Domain\User\Auth\Actions\Password;

use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Auth\Constants\AuthConstants;
use App\Domain\User\Auth\Cache\PasswordResetTokensCacheInterface;
use App\Domain\User\Auth\DTO\ResetPasswordDTO;
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
    public function run(ResetPasswordDTO $resetPasswordDTO): string
    {
        $token = $this->passwordResetTokensCache->get($resetPasswordDTO->getEmail());
        if (!$token || $token !== $resetPasswordDTO->getResetToken()) {
            throw new InvalidResetTokenException();
        }

        $user = $this->userRepository->findByEmail($resetPasswordDTO->getEmail());
        if (!$user instanceof User) {
            throw new UserNotFoundException(['email' => $resetPasswordDTO->getEmail()]);
        }

        return $this->reset($user, $resetPasswordDTO);
    }

    /**
     * @throws PasswordResetFailedException
     */
    private function reset(User $user, ResetPasswordDTO $resetPasswordDTO): string
    {
        try {
            return $this->transactionService->transaction(function () use ($user, $resetPasswordDTO) {
                $hashedPassword = $this->hashService->make($resetPasswordDTO->getPassword());
                $this->userRepository->updatePassword($user, $hashedPassword);

                $this->passwordResetTokensCache->delete($resetPasswordDTO->getEmail());
                $this->tokenService->deleteAllTokens($user);

                return $this->tokenService->createAuthToken($user, AuthConstants::DEFAULT_TOKEN_NAME);
            });
        } catch (\Throwable $e) {
            Log::error('Password reset failed', [
                'email' => $resetPasswordDTO->getEmail(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw new PasswordResetFailedException(previous: $e);
        }
    }
}
