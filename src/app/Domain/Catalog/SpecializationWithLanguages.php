<?php

declare(strict_types=1);

namespace App\Domain\Catalog;

use Illuminate\Support\Collection;

/** Доменная структура: специализация с коллекцией языков программирования. */
final readonly class SpecializationWithLanguages
{
    /**
     * @param  Collection<int, ProgrammingLanguage>  $programmingLanguages
     */
    public function __construct(
        public Specialization $specialization,
        public Collection $programmingLanguages,
    ) {}
}
