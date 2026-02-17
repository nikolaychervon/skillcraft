<?php

declare(strict_types=1);

namespace App\Domain\Auth\Actions;

use App\Domain\Auth\Services\TokenServiceInterface;
use App\Models\User;

class LogoutUserAction
{
    public function __construct(
        private readonly TokenServiceInterface $tokenService
    ) {
    }

    public function run(User $user): void
    {
        $this->tokenService->deleteCurrentToken($user);
    }
}
