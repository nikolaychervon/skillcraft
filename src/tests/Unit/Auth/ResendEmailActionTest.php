<?php

declare(strict_types=1);

namespace Tests\Unit\Auth;

use App\Domain\User\Auth\Actions\CreateNewUserAction;
use App\Domain\User\Auth\Actions\Email\ResendEmailAction;
use App\Domain\User\Auth\RequestData\CreatingUserRequestData;
use App\Domain\User\Auth\RequestData\ResendEmailRequestData;
use App\Infrastructure\Notifications\Auth\VerifyEmailForRegisterNotification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ResendEmailActionTest extends TestCase
{
    use RefreshDatabase;

    private ResendEmailAction $action;
    private CreateNewUserAction $createUserAction;
    private User $user;
    private string $email = 'test@example.com';

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = app(ResendEmailAction::class);
        $this->createUserAction = app(CreateNewUserAction::class);

        Notification::fake();

        $requestData = new CreatingUserRequestData(
            firstName: 'Иван',
            lastName: 'Петров',
            email: $this->email,
            uniqueNickname: 'test_user',
            password: 'Password123!',
            middleName: null,
        );

        $this->user = $this->createUserAction->run($requestData);
    }

    public function test_it_sends_verification_email_successfully(): void
    {
        $data = ResendEmailRequestData::fromArray(['email' => $this->email]);
        $this->action->run($data);

        Notification::assertSentTo($this->user, VerifyEmailForRegisterNotification::class);
    }

    public function test_it_does_nothing_when_email_not_found(): void
    {
        Notification::fake();

        $data = ResendEmailRequestData::fromArray(['email' => 'nonexistent@example.com']);
        $this->action->run($data);

        Notification::assertNothingSent();
    }

    public function test_it_throws_exception_when_email_already_verified(): void
    {
        $this->user->markEmailAsVerified();
        $this->expectException(\App\Domain\User\Exceptions\Email\EmailAlreadyVerifiedException::class);

        $data = ResendEmailRequestData::fromArray(['email' => $this->email]);
        $this->action->run($data);
    }

    public function test_it_does_not_send_verification_to_verified_email(): void
    {
        $this->user->markEmailAsVerified();
        Notification::fake();

        try {
            $data = ResendEmailRequestData::fromArray(['email' => $this->email]);
            $this->action->run($data);
        } catch (\App\Domain\User\Exceptions\Email\EmailAlreadyVerifiedException $e) {
        }

        Notification::assertNothingSent();
    }

    public function test_it_can_resend_multiple_times_if_not_verified(): void
    {
        $data = ResendEmailRequestData::fromArray(['email' => $this->email]);

        $this->action->run($data);
        $this->action->run($data);
        $this->action->run($data);

        Notification::assertSentToTimes($this->user, VerifyEmailForRegisterNotification::class, 3);
    }
}
