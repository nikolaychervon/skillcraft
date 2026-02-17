<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Repositories;

use App\Domain\Auth\DTO\CreatingUserDTO;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public const string MODEL = User::class;

    public function findById(int $id): ?User
    {
        return self::MODEL::query()->find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return self::MODEL::query()->where('email', $email)->first();
    }

    public function create(CreatingUserDTO $dto, string $hashedPassword): User
    {
        return self::MODEL::query()->create([
            'first_name' => $dto->getFirstName(),
            'last_name' => $dto->getLastName(),
            'middle_name' => $dto->getMiddleName(),
            'email' => $dto->getEmail(),
            'password' => $hashedPassword,
            'unique_nickname' => $dto->getUniqueNickname(),
        ]);
    }

    public function updatePassword(User $user, string $hashedPassword): void
    {
        $user->update(['password' => $hashedPassword]);
    }
}
