<?php

declare(strict_types=1);

namespace App\Domain\User\Auth\Specifications;

use App\Models\User;

/**
 * Истина, когда пользователю нельзя разрешить действие (логин, сброс пароля и т.п.).
 * Выполняется, если пользователь отсутствует или email ещё не подтверждён.
 */
final readonly class UserNotConfirmedSpecification
{
    public function isSatisfiedBy(?User $user): bool
    {
        return !$user || !$user->hasVerifiedEmail();
    }
}
