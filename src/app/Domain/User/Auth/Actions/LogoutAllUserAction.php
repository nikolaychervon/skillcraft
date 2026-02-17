<?php

declare(strict_types=1);

namespace App\Domain\User\Auth\Actions;

use App\Domain\User\Auth\Services\TokenServiceInterface;
use App\Models\User;

class LogoutAllUserAction
{
    public function __construct(
        private readonly TokenServiceInterface $tokenService
    ) {
    }

    public function run(User $user): void
    {
        $this->tokenService->deleteAllTokens($user);
    }
}
