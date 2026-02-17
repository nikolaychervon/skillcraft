<?php

declare(strict_types=1);

namespace App\Domain\Profile\Actions;

use App\Domain\Auth\Services\HashServiceInterface;
use App\Domain\Profile\DTO\ChangeDeveloperPasswordDTO;
use App\Domain\Profile\Exceptions\IncorrectCurrentPasswordException;
use App\Domain\Profile\Repositories\DeveloperProfileRepositoryInterface;
use App\Models\User as DeveloperProfile;

class ChangeDeveloperPasswordAction
{
    public function __construct(
        private readonly DeveloperProfileRepositoryInterface $developerProfileRepository,
        private readonly HashServiceInterface $hashService
    ) {
    }

    /**
     * @throws IncorrectCurrentPasswordException
     */
    public function run(DeveloperProfile $developerProfile, ChangeDeveloperPasswordDTO $dto): void
    {
        if (!$this->hashService->check($dto->getOldPassword(), $developerProfile->password)) {
            throw new IncorrectCurrentPasswordException();
        }

        $hashedPassword = $this->hashService->make($dto->getPassword());
        $this->developerProfileRepository->updatePassword($developerProfile, $hashedPassword);
    }
}
