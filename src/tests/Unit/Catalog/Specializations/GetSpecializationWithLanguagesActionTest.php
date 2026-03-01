<?php

declare(strict_types=1);

namespace Tests\Unit\Catalog\Specializations;

use App\Application\Catalog\GetSpecializationWithLanguages;
use App\Domain\Catalog\ProgrammingLanguage;
use App\Domain\Catalog\Repositories\ProgrammingLanguageRepositoryInterface;
use App\Domain\Catalog\Specialization;
use App\Domain\Catalog\SpecializationWithLanguages;
use Illuminate\Support\Collection;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tests\TestCase;

class GetSpecializationWithLanguagesActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_returns_specialization_with_languages_from_repository(): void
    {
        $specialization = new Specialization(1, 'backend', 'Backend');
        $languages = collect([
            new ProgrammingLanguage(1, 'php', 'PHP'),
            new ProgrammingLanguage(2, 'go', 'Go'),
        ]);

        $repo = Mockery::mock(ProgrammingLanguageRepositoryInterface::class);
        $repo->shouldReceive('getBySpecializationId')
            ->once()
            ->with(1)
            ->andReturn($languages);

        $action = new GetSpecializationWithLanguages($repo);

        $result = $action->run($specialization);

        $this->assertInstanceOf(SpecializationWithLanguages::class, $result);
        $this->assertSame($specialization, $result->specialization);
        $this->assertSame($languages, $result->programmingLanguages);
        $this->assertCount(2, $result->programmingLanguages);
    }

    public function test_it_returns_specialization_with_empty_languages(): void
    {
        $specialization = new Specialization(5, 'frontend', 'Frontend');
        $languages = collect([]);

        $repo = Mockery::mock(ProgrammingLanguageRepositoryInterface::class);
        $repo->shouldReceive('getBySpecializationId')
            ->once()
            ->with(5)
            ->andReturn($languages);

        $action = new GetSpecializationWithLanguages($repo);

        $result = $action->run($specialization);

        $this->assertInstanceOf(SpecializationWithLanguages::class, $result);
        $this->assertSame($specialization, $result->specialization);
        $this->assertTrue($result->programmingLanguages->isEmpty());
    }
}
