<?php

declare(strict_types=1);

namespace App\Domain\Profile\Actions;

use App\Application\Shared\Exceptions\User\Email\InvalidConfirmationLinkException;
use App\Application\Shared\Exceptions\User\UserNotFoundException;
use App\Domain\Profile\Repositories\DeveloperProfileRepositoryInterface;
use App\Models\User as DeveloperProfile;

class VerifyEmailChangeAction
{
    public function __construct(
        private readonly DeveloperProfileRepositoryInterface $developerProfileRepository
    ) {
    }

    /**
     * @throws InvalidConfirmationLinkException
     * @throws UserNotFoundException
     */
    public function run(int $id, string $hash): void
    {
        $developerProfile = $this->developerProfileRepository->findById($id);
        if (!$developerProfile instanceof DeveloperProfile) {
            throw new UserNotFoundException(['id' => $id]);
        }

        if (!$developerProfile->pending_email) {
            throw new InvalidConfirmationLinkException();
        }

        if (!hash_equals($hash, sha1($developerProfile->pending_email))) {
            throw new InvalidConfirmationLinkException();
        }

        $this->developerProfileRepository->confirmPendingEmail($developerProfile);
        $developerProfile->refresh();
    }
}
