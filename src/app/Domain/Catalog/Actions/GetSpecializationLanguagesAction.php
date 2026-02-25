<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Actions;

use App\Domain\Catalog\Repositories\ProgrammingLanguageRepositoryInterface;
use App\Models\ProgrammingLanguage;
use Illuminate\Support\Collection;

class GetSpecializationLanguagesAction
{
    public function __construct(
        private readonly ProgrammingLanguageRepositoryInterface $programmingLanguageRepository
    ) {
    }

    /** @return Collection<int, ProgrammingLanguage> */
    public function run(int $specializationId): Collection
    {
        return $this->programmingLanguageRepository->getBySpecializationId($specializationId);
    }
}
