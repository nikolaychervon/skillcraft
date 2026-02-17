<?php

declare(strict_types=1);

namespace App\Domain\User\Auth\Specifications;

use App\Models\User;

class UserNotConfirmedSpecification
{
    public function isSatisfiedBy(?User $user): bool
    {
        return !$user || !$user->hasVerifiedEmail();
    }
}
