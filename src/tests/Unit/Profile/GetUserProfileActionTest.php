<?php

declare(strict_types=1);

namespace Tests\Unit\Profile;

use App\Application\User\Profile\GetUserProfile;
use App\Domain\User\User;
use DateTimeImmutable;
use Tests\TestCase;

class GetUserProfileActionTest extends TestCase
{
    public function test_it_returns_the_same_user(): void
    {
        $user = new User(
            id: 1,
            email: 'test@example.com',
            password: 'hashed',
            firstName: 'John',
            lastName: 'Doe',
            uniqueNickname: 'johndoe',
            middleName: null,
            pendingEmail: null,
            emailVerifiedAt: new DateTimeImmutable('2024-01-01 00:00:00'),
        );

        $action = new GetUserProfile();

        $result = $action->run($user);

        $this->assertSame($user, $result);
        $this->assertSame(1, $result->id);
        $this->assertSame('test@example.com', $result->email);
    }
}
