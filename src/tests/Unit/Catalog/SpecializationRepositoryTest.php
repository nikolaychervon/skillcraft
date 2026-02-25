<?php

namespace Tests\Unit\Catalog;

use App\Models\ProgrammingLanguage;
use App\Models\Specialization;
use App\Models\Track;
use App\Infrastructure\Catalog\Repositories\SpecializationRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpecializationRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private SpecializationRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new SpecializationRepository();
    }

    public function test_get_all_returns_specializations_ordered_by_name(): void
    {
        Specialization::create(['key' => 'backend', 'name' => 'Backend']);
        Specialization::create(['key' => 'frontend', 'name' => 'Frontend']);
        Specialization::create(['key' => 'android', 'name' => 'Android']);

        $result = $this->repository->getAll();

        $this->assertCount(3, $result);
        $names = $result->pluck('name')->all();
        $this->assertSame(['Android', 'Backend', 'Frontend'], $names);
    }

    public function test_get_all_returns_empty_collection_when_no_specializations(): void
    {
        $result = $this->repository->getAll();

        $this->assertTrue($result->isEmpty());
    }

    public function test_find_by_id_returns_specialization_when_exists(): void
    {
        $spec = Specialization::create(['key' => 'backend', 'name' => 'Backend']);

        $result = $this->repository->findById($spec->id);

        $this->assertNotNull($result);
        $this->assertInstanceOf(Specialization::class, $result);
        $this->assertSame($spec->id, $result->id);
        $this->assertSame('backend', $result->key);
        $this->assertSame('Backend', $result->name);
    }

    public function test_find_by_id_returns_null_when_not_exists(): void
    {
        $result = $this->repository->findById(99999);

        $this->assertNull($result);
    }

    public function test_get_languages_by_specialization_id_returns_languages_through_tracks(): void
    {
        $spec = Specialization::create(['key' => 'backend', 'name' => 'Backend']);
        $php = ProgrammingLanguage::create(['key' => 'php', 'name' => 'PHP']);
        $go = ProgrammingLanguage::create(['key' => 'go', 'name' => 'Go']);

        Track::create([
            'key' => 'backend-php',
            'specialization_id' => $spec->id,
            'programming_language_id' => $php->id,
            'name' => 'Backend PHP',
        ]);
        Track::create([
            'key' => 'backend-go',
            'specialization_id' => $spec->id,
            'programming_language_id' => $go->id,
            'name' => 'Backend Go',
        ]);

        $result = $this->repository->getLanguagesBySpecializationId($spec->id);

        $this->assertCount(2, $result);
        $keys = $result->pluck('key')->sort()->values()->all();
        $this->assertSame(['go', 'php'], $keys);
    }

    public function test_get_languages_by_specialization_id_returns_empty_when_specialization_not_found(): void
    {
        $result = $this->repository->getLanguagesBySpecializationId(99999);

        $this->assertTrue($result->isEmpty());
    }

    public function test_get_languages_by_specialization_id_returns_empty_when_no_tracks(): void
    {
        $spec = Specialization::create(['key' => 'empty', 'name' => 'Empty']);

        $result = $this->repository->getLanguagesBySpecializationId($spec->id);

        $this->assertTrue($result->isEmpty());
    }
}
