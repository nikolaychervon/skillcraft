<?php

namespace Tests\Unit\Auth;

use App\Domain\Auth\Actions\CreateNewUserAction;
use App\Domain\Auth\Actions\RegisterUserAction;
use App\Domain\Auth\DTO\CreatingUserDTO;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Domain\Auth\Services\NotificationServiceInterface;
use App\Models\User;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tests\TestCase;

class RegisterUserActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_creates_user_when_not_exists_and_sends_verification_email(): void
    {
        $dto = new CreatingUserDTO(
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
        $user->email = $dto->getEmail();

        $repo->shouldReceive('findByEmail')
            ->once()
            ->with($dto->getEmail())
            ->andReturn(null);

        $createNewUserAction->shouldReceive('run')
            ->once()
            ->with($dto)
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

        $result = $action->run($dto);

        $this->assertSame($user, $result);
    }

    public function test_it_does_not_create_user_when_exists_but_still_sends_verification_email(): void
    {
        $dto = new CreatingUserDTO(
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
        $existingUser->email = $dto->getEmail();

        $repo->shouldReceive('findByEmail')
            ->once()
            ->with($dto->getEmail())
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

        $result = $action->run($dto);

        $this->assertSame($existingUser, $result);
    }
}
