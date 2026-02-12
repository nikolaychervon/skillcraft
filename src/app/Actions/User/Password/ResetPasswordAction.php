<?php

namespace App\Actions\User\Password;

use App\Cache\User\Auth\PasswordResetTokensCache;
use App\DTO\User\ResetPasswordDTO;
use App\Exceptions\User\Auth\InvalidResetTokenException;
use App\Exceptions\User\Auth\PasswordResetFailedException;
use App\Exceptions\User\UserNotFoundException;
use App\Models\User;
use App\Repositories\User\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordAction
{
    public function __construct(
        private UserRepository $userRepository,
        private PasswordResetTokensCache $passwordResetTokensCache,
    ) {
    }

    /**
     * @return string auth-token
     * @throws PasswordResetFailedException
     * @throws InvalidResetTokenException
     * @throws UserNotFoundException
     */
    public function run(ResetPasswordDTO $resetPasswordDTO): string
    {
        /** @var ?string $token */
        $token = $this->passwordResetTokensCache->getTokenByEmail($resetPasswordDTO->getEmail());
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
     * @return string auth-token
     * @throws PasswordResetFailedException
     */
    private function reset(User $user, ResetPasswordDTO $resetPasswordDTO): string
    {
        try {
            DB::beginTransaction();

            $user->update([
                'password' => Hash::make($resetPasswordDTO->getPassword()),
            ]);

            $this->passwordResetTokensCache->delete($resetPasswordDTO->getEmail());
            $user->tokens()->delete();

            DB::commit();
            return $user->createToken('auth_token')->plainTextToken;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw new PasswordResetFailedException();
        }
    }
}
