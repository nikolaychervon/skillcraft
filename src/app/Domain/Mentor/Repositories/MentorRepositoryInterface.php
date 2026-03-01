<?php

declare(strict_types=1);

namespace App\Domain\Mentor\Repositories;

use App\Domain\Mentor\Mentor;

interface MentorRepositoryInterface
{
    public function create(array $data): Mentor;
}
