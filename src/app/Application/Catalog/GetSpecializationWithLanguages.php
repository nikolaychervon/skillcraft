<?php

declare(strict_types=1);

namespace App\Application\Catalog;

use App\Domain\Catalog\Repositories\ProgrammingLanguageRepositoryInterface;
use App\Domain\Catalog\Specialization;
use App\Domain\Catalog\SpecializationWithLanguages;

final readonly class GetSpecializationWithLanguages
{
    public function __construct(
        private ProgrammingLanguageRepositoryInterface $programmingLanguageRepository,
    ) {}

    public function run(Specialization $specialization): SpecializationWithLanguages
    {
        $languages = $this->programmingLanguageRepository->getBySpecializationId($specialization->id);

        return new SpecializationWithLanguages($specialization, $languages);
    }
}
