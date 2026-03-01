<?php

declare(strict_types=1);

namespace App\Infrastructure\Mentor\Repositories\Cached;

use App\Domain\Mentor\Repositories\TrackRepositoryInterface;
use App\Domain\Mentor\Track;
use App\Infrastructure\Mentor\Cache\TrackCache;

final class CachedTrackRepository implements TrackRepositoryInterface
{
    public function __construct(
        private TrackRepositoryInterface $trackRepository,
        private TrackCache $trackCache,
    ) {}

    public function getBySpecializationAndLanguage(int $specializationId, int $programmingLanguageId): ?Track
    {
        $cached = $this->trackCache->getForSpecializationAndLanguage($specializationId, $programmingLanguageId);
        if ($cached !== null) {
            return $cached;
        }

        $track = $this->trackRepository->getBySpecializationAndLanguage($specializationId, $programmingLanguageId);
        if ($track === null) {
            return null;
        }

        $this->trackCache->putForSpecializationAndLanguage($specializationId, $programmingLanguageId, $track);

        return $track;
    }
}
