<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Mentor\Cache\TrackCacheInterface;
use App\Domain\Mentor\Repositories\MentorRepositoryInterface;
use App\Domain\Mentor\Repositories\TrackRepositoryInterface;
use App\Infrastructure\Mentor\Cache\TrackCache;
use App\Infrastructure\Mentor\Repositories\Cached\CachedTrackRepository;
use App\Infrastructure\Mentor\Repositories\MentorRepository;
use App\Infrastructure\Mentor\Repositories\TrackRepository;
use Illuminate\Support\ServiceProvider;

class MentorServiceProvider extends ServiceProvider
{
    public $bindings = [
        TrackCacheInterface::class => TrackCache::class,
        MentorRepositoryInterface::class => MentorRepository::class,
        TrackRepositoryInterface::class => CachedTrackRepository::class,
    ];

    public function register(): void
    {
        $this->app->when(CachedTrackRepository::class)
            ->needs(TrackRepositoryInterface::class)
            ->give(TrackRepository::class);
    }
}
