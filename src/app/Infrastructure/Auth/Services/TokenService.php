<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Services;

use App\Domain\Auth\Services\TokenServiceInterface;
use App\Models\User;

class TokenService implements TokenServiceInterface
{
    public function createAuthToken(User $user, string $tokenName = 'auth_token'): string
    {
        return $user->createToken($tokenName)->plainTextToken;
    }

    public function deleteCurrentToken(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }

    public function deleteAllTokens(User $user): void
    {
        $user->tokens()->delete();
    }
}
