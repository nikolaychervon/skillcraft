<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Repositories;

use App\Domain\User\Auth\DTO\CreatingUserDTO;
use App\Domain\User\Repositories\UserRepositoryInterface;
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

    /**
     * @param array<string, mixed> $attributes
     */
    public function update(User $user, array $attributes): User
    {
        $user->fill($attributes);
        $user->save();
        return $user;
    }

    public function updatePassword(User $user, string $hashedPassword): void
    {
        $user->update(['password' => $hashedPassword]);
    }

    public function setPendingEmail(User $user, string $email): void
    {
        $user->update(['pending_email' => $email]);
    }

    public function confirmPendingEmail(User $user): void
    {
        $user->update([
            'email' => $user->pending_email,
            'pending_email' => null,
            'email_verified_at' => now(),
        ]);
    }
}
