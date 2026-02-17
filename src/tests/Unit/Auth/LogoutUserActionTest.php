<?php

namespace Tests\Unit\Auth;

use App\Domain\Auth\Actions\LogoutUserAction;
use App\Domain\Auth\Services\TokenServiceInterface;
use App\Models\User;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tests\TestCase;

class LogoutUserActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_deletes_current_token(): void
    {
        $tokenService = Mockery::mock(TokenServiceInterface::class);
        $action = new LogoutUserAction($tokenService);

        $user = new User();

        $tokenService->shouldReceive('deleteCurrentToken')
            ->once()
            ->with($user)
            ->andReturnNull();

        $action->run($user);
    }
}

