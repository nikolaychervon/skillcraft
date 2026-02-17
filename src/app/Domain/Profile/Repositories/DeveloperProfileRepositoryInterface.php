<?php

declare(strict_types=1);

namespace App\Domain\Profile\Repositories;

use App\Models\User as DeveloperProfile;

interface DeveloperProfileRepositoryInterface
{
    /**
     * Получает разработчика (пользователя) по ID
     *
     * @param int $id
     * @return DeveloperProfile|null
     */
    public function findById(int $id): ?DeveloperProfile;

    /**
     * Обновляет данные разработчика (пользователя)
     *
     * @param DeveloperProfile $developerProfile
     * @param array $attributes
     * @return DeveloperProfile
     */
    public function update(DeveloperProfile $developerProfile, array $attributes): DeveloperProfile;

    /**
     * Обновляет пароль разработчика (пользователя)
     *
     * @param DeveloperProfile $developerProfile
     * @param string $hashedPassword
     * @return void
     */
    public function updatePassword(DeveloperProfile $developerProfile, string $hashedPassword): void;

    /**
     * Устанавливает pending email и запускает процесс подтверждения
     *
     * @param DeveloperProfile $developerProfile
     * @param string $email
     * @return void
     */
    public function setPendingEmail(DeveloperProfile $developerProfile, string $email): void;

    /**
     * Подтверждает pending email (переносит в email и чистит pending)
     *
     * @param DeveloperProfile $developerProfile
     * @return void
     */
    public function confirmPendingEmail(DeveloperProfile $developerProfile): void;
}
