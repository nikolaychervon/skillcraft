<?php

declare(strict_types=1);

namespace App\Application\User\Profile;

use App\Domain\User\Profile\RequestData\ChangeUserEmailRequestData;
use App\Domain\User\Profile\Services\ProfileNotificationServiceInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Models\User;

/**
 * Устанавливает ожидающий email и отправляет подтверждение на новый адрес.
 * Email меняется только после перехода по подписанной ссылке.
 */
final readonly class ChangeUserEmail
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private ProfileNotificationServiceInterface $notificationService,
    ) {}

    public function run(User $user, ChangeUserEmailRequestData $data): void
    {
        $this->userRepository->setPendingEmail($user, $data->email);
        $this->notificationService->sendEmailChangeVerificationNotification($user, $data->email);
    }
}
