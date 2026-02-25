<?php

declare(strict_types=1);

namespace App\Infrastructure\Catalog\Repositories;

use App\Domain\Catalog\Repositories\SpecializationRepositoryInterface;
use App\Models\Specialization;
use Illuminate\Support\Collection;

/** Eloquent-реализация без кэша; кэш навешивается декоратором. */
final class SpecializationRepository implements SpecializationRepositoryInterface
{
    public function getAll(): Collection
    {
        return Specialization::query()
            ->orderBy('name')
            ->get();
    }

    public function findById(int $id): ?Specialization
    {
        return Specialization::query()->find($id);
    }
}
