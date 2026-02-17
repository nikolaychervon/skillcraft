<?php

namespace Tests\Unit\Auth;

use App\Domain\User\Auth\Actions\LogoutAllUserAction;
use App\Domain\User\Auth\Services\TokenServiceInterface;
use App\Models\User;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tests\TestCase;

class LogoutAllUserActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_deletes_all_tokens(): void
    {
        $tokenService = Mockery::mock(TokenServiceInterface::class);
        $action = new LogoutAllUserAction($tokenService);

        $user = new User();

        $tokenService->shouldReceive('deleteAllTokens')
            ->once()
            ->with($user)
            ->andReturnNull();

        $action->run($user);
    }
}
