<?php

declare(strict_types=1);

namespace App\Infrastructure\Mentor\Repositories;

use App\Domain\Mentor\Mentor;
use App\Infrastructure\Mentor\Mappers\MentorMapper;
use App\Models\Mentor as MentorModel;
use App\Domain\Mentor\Repositories\MentorRepositoryInterface;

final class MentorRepository implements MentorRepositoryInterface
{
    public function __construct(
        private MentorMapper $mapper,
    ) {}

    public function create(array $data): Mentor
    {
        $mentorModel = MentorModel::query()->create($data);
        $mentorModel->load(['track.specialization', 'track.programmingLanguage']);

        return $this->mapper->toDomain($mentorModel);
    }
}
