<?php

namespace Tests\Unit\Profile;

use App\Domain\User\Profile\Actions\ChangeUserEmailAction;
use App\Domain\User\Profile\DTO\ChangeUserEmailDTO;
use App\Domain\User\Profile\Services\ProfileNotificationServiceInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Models\User;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tests\TestCase;

class ChangeUserEmailActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_sets_pending_email_and_sends_verification_notification(): void
    {
        $repo = Mockery::mock(UserRepositoryInterface::class);
        $notificationService = Mockery::mock(ProfileNotificationServiceInterface::class);

        $action = new ChangeUserEmailAction($repo, $notificationService);

        $user = new User();
        $dto = new ChangeUserEmailDTO('new@example.com');

        $repo->shouldReceive('setPendingEmail')
            ->once()
            ->with($user, 'new@example.com')
            ->andReturnNull();

        $notificationService->shouldReceive('sendEmailChangeVerificationNotification')
            ->once()
            ->with($user, 'new@example.com')
            ->andReturnNull();

        $action->run($user, $dto);
    }
}
