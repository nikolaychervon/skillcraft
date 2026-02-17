<?php

namespace Tests\Unit\Profile;

use App\Domain\Auth\Services\HashServiceInterface;
use App\Domain\Profile\Actions\ChangeDeveloperPasswordAction;
use App\Domain\Profile\DTO\ChangeDeveloperPasswordDTO;
use App\Domain\Profile\Exceptions\IncorrectCurrentPasswordException;
use App\Domain\Profile\Repositories\DeveloperProfileRepositoryInterface;
use App\Models\User as DeveloperProfile;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tests\TestCase;

class ChangeDeveloperPasswordActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_throws_when_old_password_incorrect(): void
    {
        $repo = Mockery::mock(DeveloperProfileRepositoryInterface::class);
        $hash = Mockery::mock(HashServiceInterface::class);
        $action = new ChangeDeveloperPasswordAction($repo, $hash);

        $profile = new DeveloperProfile();
        $profile->password = 'hashed';

        $dto = new ChangeDeveloperPasswordDTO('wrong', 'new');

        $hash->shouldReceive('check')->once()->with('wrong', 'hashed')->andReturnFalse();
        $repo->shouldNotReceive('updatePassword');

        $this->expectException(IncorrectCurrentPasswordException::class);
        $action->run($profile, $dto);
    }

    public function test_it_updates_password_when_old_password_correct(): void
    {
        $repo = Mockery::mock(DeveloperProfileRepositoryInterface::class);
        $hash = Mockery::mock(HashServiceInterface::class);
        $action = new ChangeDeveloperPasswordAction($repo, $hash);

        $profile = new DeveloperProfile();
        $profile->password = 'hashed';

        $dto = new ChangeDeveloperPasswordDTO('old', 'new');

        $hash->shouldReceive('check')->once()->with('old', 'hashed')->andReturnTrue();
        $hash->shouldReceive('make')->once()->with('new')->andReturn('new_hashed');

        $repo->shouldReceive('updatePassword')->once()->with($profile, 'new_hashed')->andReturnNull();

        $action->run($profile, $dto);
    }
}
