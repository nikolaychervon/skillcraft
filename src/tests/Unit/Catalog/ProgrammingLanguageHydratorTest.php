<?php

namespace Tests\Unit\Catalog;

use App\Infrastructure\Catalog\Hydrators\ProgrammingLanguageHydrator;
use App\Models\ProgrammingLanguage;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ProgrammingLanguageHydratorTest extends TestCase
{
    private ProgrammingLanguageHydrator $hydrator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->hydrator = new ProgrammingLanguageHydrator();
    }

    public function test_to_array_returns_model_attributes(): void
    {
        $model = new ProgrammingLanguage();
        $model->setRawAttributes([
            'id' => 1,
            'key' => 'php',
            'name' => 'PHP',
            'created_at' => '2026-01-01 00:00:00',
            'updated_at' => '2026-01-01 00:00:00',
        ]);

        $result = $this->hydrator->toArray($model);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('key', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertSame(1, $result['id']);
        $this->assertSame('php', $result['key']);
        $this->assertSame('PHP', $result['name']);
    }

    public function test_from_array_returns_model_with_attributes(): void
    {
        $data = [
            'id' => 2,
            'key' => 'javascript',
            'name' => 'JavaScript',
            'created_at' => '2026-01-02 00:00:00',
            'updated_at' => '2026-01-02 00:00:00',
        ];

        $model = $this->hydrator->fromArray($data);

        $this->assertInstanceOf(ProgrammingLanguage::class, $model);
        $this->assertSame(2, $model->id);
        $this->assertSame('javascript', $model->key);
        $this->assertSame('JavaScript', $model->name);
    }

    public function test_to_array_collection_serializes_collection(): void
    {
        $models = collect([
            (new ProgrammingLanguage())->setRawAttributes(['id' => 1, 'key' => 'php', 'name' => 'PHP', 'created_at' => null, 'updated_at' => null]),
            (new ProgrammingLanguage())->setRawAttributes(['id' => 2, 'key' => 'js', 'name' => 'JS', 'created_at' => null, 'updated_at' => null]),
        ]);

        $result = $this->hydrator->toArrayCollection($models);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertSame(1, $result[0]['id']);
        $this->assertSame('php', $result[0]['key']);
        $this->assertSame(2, $result[1]['id']);
    }

    public function test_from_array_collection_deserializes_to_collection(): void
    {
        $data = [
            ['id' => 10, 'key' => 'go', 'name' => 'Go', 'created_at' => null, 'updated_at' => null],
            ['id' => 20, 'key' => 'rust', 'name' => 'Rust', 'created_at' => null, 'updated_at' => null],
        ];

        $collection = $this->hydrator->fromArrayCollection($data);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertCount(2, $collection);
        $this->assertInstanceOf(ProgrammingLanguage::class, $collection->first());
        $this->assertSame(10, $collection->first()->id);
        $this->assertSame(20, $collection->get(1)->id);
    }

    public function test_roundtrip_to_array_and_from_array_preserves_data(): void
    {
        $model = new ProgrammingLanguage();
        $model->setRawAttributes([
            'id' => 7,
            'key' => 'roundtrip',
            'name' => 'Roundtrip Lang',
            'created_at' => '2026-02-01 12:00:00',
            'updated_at' => '2026-02-01 12:00:00',
        ]);

        $array = $this->hydrator->toArray($model);
        $restored = $this->hydrator->fromArray($array);

        $this->assertSame($model->id, $restored->id);
        $this->assertSame($model->key, $restored->key);
        $this->assertSame($model->name, $restored->name);
    }
}
