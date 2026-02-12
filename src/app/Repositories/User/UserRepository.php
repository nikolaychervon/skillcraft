<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository
{
    public const string MODEL = User::class;

    public function findById(int $id): ?User
    {
        return self::MODEL::query()->findOrFail($id);
    }

    public function findByEmail(string $email): ?User
    {
        return self::MODEL::query()->where('email', $email)->first();
    }
}
