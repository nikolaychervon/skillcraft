<?php

namespace App\Actions\User\Email;

use App\Exceptions\User\Email\EmailAlreadyVerifiedException;
use App\Exceptions\User\Email\InvalidConfirmationLinkException;
use App\Exceptions\User\UserNotFoundException;
use App\Models\User;
use App\Repositories\User\UserRepository;

class VerifyEmailAction
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    /**
     * @return string auth-token
     * @throws InvalidConfirmationLinkException
     * @throws EmailAlreadyVerifiedException
     * @throws UserNotFoundException
     */
    public function run(int $id, string $hash): string
    {
        $user = $this->userRepository->findById($id);
        if (!$user instanceof User) {
            throw new UserNotFoundException(['id' => $id]);
        }

        if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
            throw new InvalidConfirmationLinkException();
        }

        if ($user->hasVerifiedEmail()) {
            throw new EmailAlreadyVerifiedException();
        }

        $user->markEmailAsVerified();
        return $user->createToken('auth_token')->plainTextToken;
    }
}
