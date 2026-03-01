<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Resources\Catalog;

use App\Domain\Catalog\ProgrammingLanguage;
use App\Domain\Catalog\Specialization;
use App\Domain\Catalog\SpecializationWithLanguages;
use App\Http\Resources\Catalog\SpecializationWithLanguagesResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Tests\TestCase;

class SpecializationWithLanguagesResourceTest extends TestCase
{
    public function test_it_transforms_specialization_with_languages_to_array(): void
    {
        $specialization = new Specialization(1, 'backend', 'Backend');
        $languages = collect([
            new ProgrammingLanguage(1, 'php', 'PHP'),
            new ProgrammingLanguage(2, 'go', 'Go'),
        ]);
        $data = new SpecializationWithLanguages($specialization, $languages);

        $resource = new SpecializationWithLanguagesResource($data);
        $request = Request::create('/api/v1/catalog/specializations/1/languages');
        $array = $resource->toArray($request);

        $this->assertSame(1, $array['spec_id']);
        $this->assertSame('backend', $array['spec_key']);
        $this->assertSame('Backend', $array['spec_name']);

        $pl = $array['programming_languages'];
        $this->assertInstanceOf(AnonymousResourceCollection::class, $pl);
        $resolved = $pl->resolve($request);
        $this->assertCount(2, $resolved);

        $this->assertSame(1, $resolved[0]['id']);
        $this->assertSame('php', $resolved[0]['key']);
        $this->assertSame('PHP', $resolved[0]['name']);
        $this->assertSame(2, $resolved[1]['id']);
        $this->assertSame('go', $resolved[1]['key']);
        $this->assertSame('Go', $resolved[1]['name']);
    }

    public function test_it_transforms_empty_languages(): void
    {
        $specialization = new Specialization(5, 'frontend', 'Frontend');
        $data = new SpecializationWithLanguages($specialization, collect([]));

        $resource = new SpecializationWithLanguagesResource($data);
        $array = $resource->toArray(Request::create('/test'));

        $this->assertSame(5, $array['spec_id']);
        $this->assertSame('frontend', $array['spec_key']);
        $this->assertSame('Frontend', $array['spec_name']);

        $pl = $array['programming_languages'];
        $this->assertInstanceOf(AnonymousResourceCollection::class, $pl);
        $this->assertCount(0, $pl->resolve(Request::create('/test')));
    }
}
