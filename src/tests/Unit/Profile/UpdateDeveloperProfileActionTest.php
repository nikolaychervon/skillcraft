<?php

namespace Tests\Unit\Profile;

use App\Domain\Profile\Actions\UpdateDeveloperProfileAction;
use App\Domain\Profile\DTO\UpdateDeveloperProfileDTO;
use App\Domain\Profile\Repositories\DeveloperProfileRepositoryInterface;
use App\Models\User as DeveloperProfile;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tests\TestCase;

class UpdateDeveloperProfileActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_updates_all_profile_fields(): void
    {
        $repo = Mockery::mock(DeveloperProfileRepositoryInterface::class);
        $action = new UpdateDeveloperProfileAction($repo);

        $profile = new DeveloperProfile();
        $dto = new UpdateDeveloperProfileDTO(
            firstName: 'Иван',
            lastName: 'Петров',
            middleName: null,
            uniqueNickname: 'ivan_dev',
        );

        $repo->shouldReceive('update')
            ->once()
            ->with($profile, [
                'first_name' => 'Иван',
                'last_name' => 'Петров',
                'middle_name' => null,
                'unique_nickname' => 'ivan_dev',
            ])
            ->andReturn($profile);

        $result = $action->run($profile, $dto);
        $this->assertSame($profile, $result);
    }
}
