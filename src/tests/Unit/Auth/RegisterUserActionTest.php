<?php

namespace Tests\Unit\Auth;

use App\Domain\User\Auth\Actions\CreateNewUserAction;
use App\Domain\User\Auth\Actions\RegisterUserAction;
use App\Domain\User\Auth\RequestData\CreatingUserRequestData;
use App\Domain\User\Auth\Services\NotificationServiceInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Models\User;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tests\TestCase;

class RegisterUserActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_creates_user_when_not_exists_and_sends_verification_email(): void
    {
        $requestData = new CreatingUserRequestData(
            firstName: 'Иван',
            lastName: 'Петров',
            email: 'ivan@example.com',
            uniqueNickname: 'ivan_petrov',
            password: 'Password123!',
            middleName: null
        );

        $repo = Mockery::mock(UserRepositoryInterface::class);
        $createNewUserAction = Mockery::mock(CreateNewUserAction::class);
        $notificationService = Mockery::mock(NotificationServiceInterface::class);

        $user = new User();
        $user->id = 123;
        $user->email = $requestData->email;

        $repo->shouldReceive('findByEmail')
            ->once()
            ->with($requestData->email)
            ->andReturn(null);

        $createNewUserAction->shouldReceive('run')
            ->once()
            ->with($requestData)
            ->andReturn($user);

        $notificationService->shouldReceive('sendEmailVerificationNotification')
            ->once()
            ->with($user)
            ->andReturnNull();

        $action = new RegisterUserAction(
            userRepository: $repo,
            createNewUserAction: $createNewUserAction,
            notificationService: $notificationService
        );

        $result = $action->run($requestData);

        $this->assertSame($user, $result);
    }

    public function test_it_does_not_create_user_when_exists_but_still_sends_verification_email(): void
    {
        $requestData = new CreatingUserRequestData(
            firstName: 'Иван',
            lastName: 'Петров',
            email: 'ivan@example.com',
            uniqueNickname: 'ivan_petrov',
            password: 'Password123!',
            middleName: null
        );

        $repo = Mockery::mock(UserRepositoryInterface::class);
        $createNewUserAction = Mockery::mock(CreateNewUserAction::class);
        $notificationService = Mockery::mock(NotificationServiceInterface::class);

        $existingUser = new User();
        $existingUser->id = 456;
        $existingUser->email = $requestData->email;

        $repo->shouldReceive('findByEmail')
            ->once()
            ->with($requestData->email)
            ->andReturn($existingUser);

        $createNewUserAction->shouldNotReceive('run');

        $notificationService->shouldReceive('sendEmailVerificationNotification')
            ->once()
            ->with($existingUser)
            ->andReturnNull();

        $action = new RegisterUserAction(
            userRepository: $repo,
            createNewUserAction: $createNewUserAction,
            notificationService: $notificationService
        );

        $result = $action->run($requestData);

        $this->assertSame($existingUser, $result);
    }
}
