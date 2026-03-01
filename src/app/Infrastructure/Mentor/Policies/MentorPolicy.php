<?php

declare(strict_types=1);

namespace App\Infrastructure\Mentor\Policies;

use App\Models\Mentor;
use App\Models\User;

final class MentorPolicy
{
    public function update(User $user, Mentor $mentor): bool
    {
        return $mentor->user_id === $user->id;
    }

    public function delete(User $user, Mentor $mentor): bool
    {
        return $mentor->user_id === $user->id;
    }
}
