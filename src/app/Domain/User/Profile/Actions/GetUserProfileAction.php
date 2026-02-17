<?php

declare(strict_types=1);

namespace App\Domain\User\Profile\Actions;

use App\Models\User;

class GetUserProfileAction
{
    public function run(User $user): User
    {
        return $user;
    }
}
