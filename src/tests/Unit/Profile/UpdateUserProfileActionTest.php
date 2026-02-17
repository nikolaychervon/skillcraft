<?php

namespace Tests\Unit\Profile;

use App\Domain\User\Profile\Actions\UpdateUserProfileAction;
use App\Domain\User\Profile\DTO\UpdateUserProfileDTO;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Models\User;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tests\TestCase;

class UpdateUserProfileActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_updates_all_profile_fields(): void
    {
        $repo = Mockery::mock(UserRepositoryInterface::class);
        $action = new UpdateUserProfileAction($repo);

        $user = new User();
        $dto = new UpdateUserProfileDTO(
            firstName: 'Иван',
            lastName: 'Петров',
            middleName: null,
            uniqueNickname: 'ivan_dev',
        );

        $repo->shouldReceive('update')
            ->once()
            ->with($user, [
                'first_name' => 'Иван',
                'last_name' => 'Петров',
                'middle_name' => null,
                'unique_nickname' => 'ivan_dev',
            ])
            ->andReturn($user);

        $result = $action->run($user, $dto);
        $this->assertSame($user, $result);
    }
}
