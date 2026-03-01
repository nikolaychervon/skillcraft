<?php

declare(strict_types=1);

namespace Tests\Feature\Catalog;

use App\Models\ProgrammingLanguage;
use App\Models\Specialization;
use App\Models\Track;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogControllerTest extends TestCase
{
    use RefreshDatabase;

    private const string SPECIALIZATIONS_API = '/api/v1/catalog/specializations';
    private const string SPECIALIZATION_LANGUAGES_API = '/api/v1/catalog/specializations/%d/languages';

    public function test_specializations_returns_empty_list_when_no_data(): void
    {
        $response = $this->getJson(self::SPECIALIZATIONS_API);

        $response->assertStatus(200)->assertJsonPath('success', true)->assertJsonPath('data', []);
    }

    public function test_specializations_returns_list_ordered_by_name(): void
    {
        Specialization::create(['key' => 'backend', 'name' => 'Backend']);
        Specialization::create(['key' => 'frontend', 'name' => 'Frontend']);
        Specialization::create(['key' => 'android', 'name' => 'Android']);

        $response = $this->getJson(self::SPECIALIZATIONS_API);

        $response->assertStatus(200)->assertJsonPath('success', true)->assertJsonCount(3, 'data');
        $data = $response->json('data');
        $this->assertSame(['Android', 'Backend', 'Frontend'], array_column($data, 'name'));
        $this->assertArrayHasKey('id', $data[0]);
        $this->assertArrayHasKey('key', $data[0]);
        $this->assertArrayHasKey('name', $data[0]);
    }

    public function test_specialization_languages_returns_empty_languages_when_no_tracks(): void
    {
        $spec = Specialization::create(['key' => 'backend', 'name' => 'Backend']);

        $response = $this->getJson(sprintf(self::SPECIALIZATION_LANGUAGES_API, $spec->id));

        $response->assertStatus(200)
            ->assertJsonPath('data.spec_id', $spec->id)
            ->assertJsonPath('data.spec_key', 'backend')
            ->assertJsonPath('data.spec_name', 'Backend')
            ->assertJsonPath('data.programming_languages', []);
    }

    public function test_specialization_languages_returns_languages_through_tracks(): void
    {
        $spec = Specialization::create(['key' => 'backend', 'name' => 'Backend']);
        $php = ProgrammingLanguage::create(['key' => 'php', 'name' => 'PHP']);
        $go = ProgrammingLanguage::create(['key' => 'go', 'name' => 'Go']);
        Track::create(['key' => 'backend-php', 'specialization_id' => $spec->id, 'programming_language_id' => $php->id, 'name' => 'Backend PHP']);
        Track::create(['key' => 'backend-go', 'specialization_id' => $spec->id, 'programming_language_id' => $go->id, 'name' => 'Backend Go']);

        $response = $this->getJson(sprintf(self::SPECIALIZATION_LANGUAGES_API, $spec->id));

        $response->assertStatus(200)
            ->assertJsonPath('data.spec_id', $spec->id)
            ->assertJsonPath('data.spec_key', 'backend')
            ->assertJsonPath('data.spec_name', 'Backend')
            ->assertJsonCount(2, 'data.programming_languages');
        $keys = array_column($response->json('data.programming_languages'), 'key');
        sort($keys);
        $this->assertSame(['go', 'php'], $keys);
    }

    public function test_specialization_languages_returns_404_for_nonexistent_specialization(): void
    {
        $this->getJson(sprintf(self::SPECIALIZATION_LANGUAGES_API, 99999))->assertStatus(404);
    }
}
