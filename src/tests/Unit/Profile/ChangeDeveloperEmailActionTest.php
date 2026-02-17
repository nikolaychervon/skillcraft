<?php

namespace Tests\Unit\Profile;

use App\Domain\Profile\Actions\ChangeDeveloperEmailAction;
use App\Domain\Profile\DTO\ChangeDeveloperEmailDTO;
use App\Domain\Profile\Repositories\DeveloperProfileRepositoryInterface;
use App\Domain\Profile\Services\DeveloperProfileNotificationServiceInterface;
use App\Models\User as DeveloperProfile;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tests\TestCase;

class ChangeDeveloperEmailActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_sets_pending_email_and_sends_verification_notification(): void
    {
        $repo = Mockery::mock(DeveloperProfileRepositoryInterface::class);
        $notificationService = Mockery::mock(DeveloperProfileNotificationServiceInterface::class);

        $action = new ChangeDeveloperEmailAction($repo, $notificationService);

        $profile = new DeveloperProfile();
        $dto = new ChangeDeveloperEmailDTO('new@example.com');

        $repo->shouldReceive('setPendingEmail')
            ->once()
            ->with($profile, 'new@example.com')
            ->andReturnNull();

        $notificationService->shouldReceive('sendEmailChangeVerificationNotification')
            ->once()
            ->with($profile, 'new@example.com')
            ->andReturnNull();

        $action->run($profile, $dto);
    }
}
