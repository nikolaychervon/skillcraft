<?php

declare(strict_types=1);

namespace App\Domain\Auth\Actions\Email;

use App\Application\Shared\Exceptions\User\Email\EmailAlreadyVerifiedException;
use App\Domain\Auth\DTO\ResendEmailDTO;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Domain\Auth\Services\NotificationServiceInterface;
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
