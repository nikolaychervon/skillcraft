<?php

declare(strict_types=1);

namespace Tests\Unit\Catalog\ProgrammingLanguages;

use App\Domain\Catalog\Cache\CatalogCacheInterface;
use App\Domain\Catalog\ProgrammingLanguage;
use App\Domain\Catalog\Repositories\ProgrammingLanguageRepositoryInterface;
use App\Infrastructure\Catalog\Repositories\Cached\CachedProgrammingLanguageRepository;
use Illuminate\Support\Collection;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tests\TestCase;

class CachedProgrammingLanguageRepositoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_get_by_specialization_id_returns_cached_languages_when_cache_hit(): void
    {
        $cachedLanguages = collect([new ProgrammingLanguage(1, 'php', 'PHP')]);

        $cache = Mockery::mock(CatalogCacheInterface::class);
        $cache->shouldReceive('getSpecializationLanguages')
            ->once()
            ->with(5)
            ->andReturn($cachedLanguages);
        $cache->shouldNotReceive('putSpecializationLanguages');

        $innerRepo = Mockery::mock(ProgrammingLanguageRepositoryInterface::class);
        $innerRepo->shouldNotReceive('getBySpecializationId');

        $repo = new CachedProgrammingLanguageRepository($innerRepo, $cache);

        $result = $repo->getBySpecializationId(5);

        $this->assertSame($cachedLanguages, $result);
        $this->assertCount(1, $result);
        $this->assertSame(1, $result->first()->id);
        $this->assertSame('php', $result->first()->key);
    }

    public function test_get_by_specialization_id_calls_inner_and_puts_to_cache_when_cache_miss(): void
    {
        $fromDb = collect([
            new ProgrammingLanguage(1, 'js', 'JavaScript'),
        ]);

        $cache = Mockery::mock(CatalogCacheInterface::class);
        $cache->shouldReceive('getSpecializationLanguages')
            ->once()
            ->with(3)
            ->andReturn(null);
        $cache->shouldReceive('putSpecializationLanguages')
            ->once()
            ->with(3, $fromDb);

        $innerRepo = Mockery::mock(ProgrammingLanguageRepositoryInterface::class);
        $innerRepo->shouldReceive('getBySpecializationId')
            ->once()
            ->with(3)
            ->andReturn($fromDb);

        $repo = new CachedProgrammingLanguageRepository($innerRepo, $cache);

        $result = $repo->getBySpecializationId(3);

        $this->assertSame($fromDb, $result);
    }
}
