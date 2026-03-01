<?php

declare(strict_types=1);

/**
 * Справочники ментора: специализации, языки, треки.
 * Единственный источник правды для сидеров — добавляй сюда и перезапускай MentorSeeder.
 *
 * @see Database\Seeders\MentorSeeder
 */
return [
    'specializations' => [
        ['key' => 'backend', 'name' => 'Backend'],
        ['key' => 'frontend', 'name' => 'Frontend'],
    ],

    'programming_languages' => [
        ['key' => 'php', 'name' => 'PHP'],
        ['key' => 'python', 'name' => 'Python'],
        ['key' => 'javascript', 'name' => 'JavaScript'],
    ],

    'tracks' => [
        ['key' => 'backend_php', 'specialization' => 'backend', 'programming_language' => 'php', 'name' => 'PHP Backend'],
        ['key' => 'backend_python', 'specialization' => 'backend', 'programming_language' => 'python', 'name' => 'Python Backend'],
        ['key' => 'frontend_javascript', 'specialization' => 'frontend', 'programming_language' => 'javascript', 'name' => 'JavaScript Frontend'],
    ],
];
