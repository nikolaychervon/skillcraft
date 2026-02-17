<?php

declare(strict_types=1);

namespace App\Domain\User\Auth\Actions\Email;

use App\Domain\User\Exceptions\Email\EmailAlreadyVerifiedException;
use App\Domain\User\Auth\DTO\ResendEmailDTO;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\Auth\Services\NotificationServiceInterface;
use App\Models\User;

class ResendEmailAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly NotificationServiceInterface $notificationService
    ) {
    }

    /**
     * @throws EmailAlreadyVerifiedException
     */
    public function run(ResendEmailDTO $resendEmailDTO): void
    {
        $user = $this->userRepository->findByEmail($resendEmailDTO->getEmail());
        if (!$user instanceof User) {
            return;
        }

        if ($user->hasVerifiedEmail()) {
            throw new EmailAlreadyVerifiedException();
        }

        $this->notificationService->sendEmailVerificationNotification($user);
    }
}
