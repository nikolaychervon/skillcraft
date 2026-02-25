<?php

declare(strict_types=1);

namespace App\Infrastructure\Catalog\Repositories;

use App\Domain\Catalog\Repositories\ProgrammingLanguageRepositoryInterface;
use App\Models\ProgrammingLanguage;
use App\Models\Specialization;
use Illuminate\Support\Collection;

final class ProgrammingLanguageRepository implements ProgrammingLanguageRepositoryInterface
{
    public function getBySpecializationId(int $specializationId): Collection
    {
        $specialization = Specialization::query()
            ->with('programmingLanguages')
            ->find($specializationId);

        return $specialization !== null
            ? $specialization->programmingLanguages
            : collect();
    }
}

