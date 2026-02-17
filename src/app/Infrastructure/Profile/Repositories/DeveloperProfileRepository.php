<?php

declare(strict_types=1);

namespace App\Infrastructure\Profile\Repositories;

use App\Domain\Profile\Repositories\DeveloperProfileRepositoryInterface;
use App\Models\User as DeveloperProfile;

class DeveloperProfileRepository implements DeveloperProfileRepositoryInterface
{
    public function findById(int $id): ?DeveloperProfile
    {
        return DeveloperProfile::query()->find($id);
    }

    public function update(DeveloperProfile $developerProfile, array $attributes): DeveloperProfile
    {
        $developerProfile->fill($attributes);
        $developerProfile->save();
        return $developerProfile;
    }

    public function updatePassword(DeveloperProfile $developerProfile, string $hashedPassword): void
    {
        $developerProfile->update(['password' => $hashedPassword]);
    }

    public function setPendingEmail(DeveloperProfile $developerProfile, string $email): void
    {
        $developerProfile->update([
            'pending_email' => $email,
        ]);
    }

    public function confirmPendingEmail(DeveloperProfile $developerProfile): void
    {
        if (!$developerProfile->pending_email) {
            return;
        }

        $developerProfile->update([
            'email' => $developerProfile->pending_email,
            'pending_email' => null,
            'email_verified_at' => now(),
        ]);
    }
}
