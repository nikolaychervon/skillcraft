<?php

namespace Tests\Unit\Catalog\Specializations;

use App\Infrastructure\Catalog\Hydrators\SpecializationHydrator;
use App\Models\Specialization;
use Illuminate\Support\Collection;
use Tests\TestCase;

class SpecializationHydratorTest extends TestCase
{
    private SpecializationHydrator $hydrator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->hydrator = new SpecializationHydrator();
    }

    public function test_to_array_returns_model_attributes(): void
    {
        $model = new Specialization();
        $model->setRawAttributes([
            'id' => 1,
            'key' => 'backend',
            'name' => 'Backend',
            'created_at' => '2026-01-01 00:00:00',
            'updated_at' => '2026-01-01 00:00:00',
        ]);

        $result = $this->hydrator->toArray($model);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('key', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertSame(1, $result['id']);
        $this->assertSame('backend', $result['key']);
        $this->assertSame('Backend', $result['name']);
    }

    public function test_from_array_returns_model_with_attributes(): void
    {
        $data = [
            'id' => 2,
            'key' => 'frontend',
            'name' => 'Frontend',
            'created_at' => '2026-01-02 00:00:00',
            'updated_at' => '2026-01-02 00:00:00',
        ];

        $model = $this->hydrator->fromArray($data);

        $this->assertInstanceOf(Specialization::class, $model);
        $this->assertSame(2, $model->id);
        $this->assertSame('frontend', $model->key);
        $this->assertSame('Frontend', $model->name);
    }

    public function test_to_array_collection_serializes_collection(): void
    {
        $models = collect([
            (new Specialization())->setRawAttributes(['id' => 1, 'key' => 'a', 'name' => 'A', 'created_at' => null, 'updated_at' => null]),
            (new Specialization())->setRawAttributes(['id' => 2, 'key' => 'b', 'name' => 'B', 'created_at' => null, 'updated_at' => null]),
        ]);

        $result = $this->hydrator->toArrayCollection($models);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertSame(1, $result[0]['id']);
        $this->assertSame('a', $result[0]['key']);
        $this->assertSame(2, $result[1]['id']);
        $this->assertSame('b', $result[1]['key']);
    }

    public function test_from_array_collection_deserializes_to_collection(): void
    {
        $data = [
            ['id' => 10, 'key' => 'x', 'name' => 'X', 'created_at' => null, 'updated_at' => null],
            ['id' => 20, 'key' => 'y', 'name' => 'Y', 'created_at' => null, 'updated_at' => null],
        ];

        $collection = $this->hydrator->fromArrayCollection($data);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertCount(2, $collection);
        $this->assertInstanceOf(Specialization::class, $collection->first());
        $this->assertSame(10, $collection->first()->id);
        $this->assertSame(20, $collection->get(1)->id);
    }

    public function test_roundtrip_to_array_and_from_array_preserves_data(): void
    {
        $model = new Specialization();
        $model->setRawAttributes([
            'id' => 7,
            'key' => 'roundtrip',
            'name' => 'Roundtrip',
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
