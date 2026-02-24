<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ProgrammingLanguage;
use App\Models\Specialization;
use App\Models\Track;
use Illuminate\Database\Seeder;

class MentorSeeder extends Seeder
{
    public function run(): void
    {
        $tracks = config('mentor.tracks', []);

        $specByKey = Specialization::query()->get()->keyBy('key');
        $langByKey = ProgrammingLanguage::query()->get()->keyBy('key');

        foreach ($tracks as $item) {
            $spec = $specByKey->get($item['specialization']);
            $lang = $langByKey->get($item['programming_language']);
            if (!$spec || !$lang) {
                continue;
            }
            Track::query()->updateOrCreate(
                ['key' => $item['key']],
                [
                    'specialization_id' => $spec->id,
                    'programming_language_id' => $lang->id,
                    'name' => $item['name'],
                ]
            );
        }
    }
}
