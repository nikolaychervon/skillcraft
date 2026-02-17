<?php

declare(strict_types=1);

namespace App\Domain\Auth\Specifications;

use App\Models\User;

/**
 * Проверка на неподтвержденного пользователя
 */
class UserNotConfirmedSpecification
{
    public function isSatisfiedBy(?User $user): bool
    {
        return !$user || !$user->hasVerifiedEmail();
    }
}
