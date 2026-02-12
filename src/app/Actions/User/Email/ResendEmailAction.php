<?php

namespace App\Actions\User\Email;

use App\Exceptions\User\Email\EmailAlreadyVerifiedException;
use App\Models\User;
use App\Notifications\User\VerifyEmailForRegisterNotification;
use App\Repositories\User\UserRepository;

class ResendEmailAction
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    /**
     * @throws EmailAlreadyVerifiedException
     */
    public function run(string $email): void
    {
        $user = $this->userRepository->findByEmail($email);
        if (!$user instanceof User) {
            return;
        }

        if ($user->hasVerifiedEmail()) {
            throw new EmailAlreadyVerifiedException();
        }

        $user->notify(new VerifyEmailForRegisterNotification());
    }
}
