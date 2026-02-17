<?php

declare(strict_types=1);

namespace App\Domain\Auth\Actions\Password;

use App\Domain\Auth\Cache\PasswordResetTokensCacheInterface;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Domain\Auth\Services\NotificationServiceInterface;
use App\Domain\Auth\Services\TokenGeneratorInterface;
use App\Domain\Auth\Specifications\UserNotConfirmedSpecification;

class SendPasswordResetLinkAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly PasswordResetTokensCacheInterface $passwordResetTokensCache,
        private readonly NotificationServiceInterface $notificationService,
        private readonly TokenGeneratorInterface $tokenGenerator,
        private readonly UserNotConfirmedSpecification $userNotConfirmedSpecification
    ) {
    }

    public function run(string $email): void
    {
        $user = $this->userRepository->findByEmail($email);
        if ($this->userNotConfirmedSpecification->isSatisfiedBy($user)) {
            return;
        }

        $resetToken = $this->tokenGenerator->generate(64);
        $this->passwordResetTokensCache->store($email, $resetToken);

        $this->notificationService->sendPasswordResetNotification($user, $email, $resetToken);
    }
}
