<?php

declare(strict_types=1);

namespace App\Domain\Auth\Repositories;

use App\Domain\Auth\DTO\CreatingUserDTO;
use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * Получает пользователя по ID, или отдает null, если пользователь не найден
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User;

    /**
     * Получает пользователя по Email, или отдает null, если пользователь не найден
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * Создает нового пользователя
     *
     * @param CreatingUserDTO $dto
     * @param string $hashedPassword
     * @return User
     */
    public function create(CreatingUserDTO $dto, string $hashedPassword): User;

    /**
     * Обновляет пароль пользователя
     *
     * @param User $user
     * @param string $hashedPassword
     * @return void
     */
    public function updatePassword(User $user, string $hashedPassword): void;
}
