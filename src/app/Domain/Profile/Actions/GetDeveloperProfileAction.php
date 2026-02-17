<?php

declare(strict_types=1);

namespace App\Domain\Profile\Actions;

use App\Models\User as DeveloperProfile;

class GetDeveloperProfileAction
{
    public function run(DeveloperProfile $developerProfile): DeveloperProfile
    {
        return $developerProfile;
    }
}
