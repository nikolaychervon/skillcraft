<?php

declare(strict_types=1);

namespace App\Domain\Profile\Actions;

use App\Domain\Profile\DTO\UpdateDeveloperProfileDTO;
use App\Domain\Profile\Repositories\DeveloperProfileRepositoryInterface;
use App\Models\User as DeveloperProfile;

class UpdateDeveloperProfileAction
{
    public function __construct(
        private readonly DeveloperProfileRepositoryInterface $developerProfileRepository
    ) {
    }

    public function run(DeveloperProfile $developerProfile, UpdateDeveloperProfileDTO $dto): DeveloperProfile
    {
        return $this->developerProfileRepository->update($developerProfile, [
            'first_name' => $dto->getFirstName(),
            'last_name' => $dto->getLastName(),
            'middle_name' => $dto->getMiddleName(),
            'unique_nickname' => $dto->getUniqueNickname(),
        ]);
    }
}
