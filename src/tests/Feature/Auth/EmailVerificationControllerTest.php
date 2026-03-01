<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Domain\User\Exceptions\Email\EmailAlreadyVerifiedException;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Tests\Concerns\ApiAssertions;
use Tests\Concerns\CreatesVerifiedUser;
use Tests\TestCase;

class EmailVerificationControllerTest extends TestCase
{
    use ApiAssertions;
    use CreatesVerifiedUser;
    use RefreshDatabase;

    private const string EMAIL_RESEND_API = '/api/v1/email/resend';
    private const string EMAIL_VERIFY_API = '/api/v1/email/verify';

    private User $user;
    private string $verificationUrl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createUnverifiedUser([
            'email' => 'verify@example.com',
            'unique_nickname' => 'verify_controller',
        ]);
        $this->verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $this->user->id, 'hash' => sha1($this->user->email)]
        );
    }

    public function test_it_verifies_email_successfully(): void
    {
        $response = $this->getJson($this->verificationUrl);

        $this->assertApiSuccess($response, 200, __('messages.email-confirmed'));
        $response->assertJsonStructure(['data' => ['token']]);
        $this->assertNotEmpty($response->json('data.token'));
        $this->user->refresh();
        $this->assertNotNull($this->user->email_verified_at);
    }

    public function test_it_returns_error_on_invalid_signature(): void
    {
        $invalidUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $this->user->id, 'hash' => 'invalid-hash']
        );

        $this->getJson($invalidUrl)->assertStatus(400);
    }

    public function test_it_returns_error_on_expired_link(): void
    {
        $expiredUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->subMinutes(1),
            ['id' => $this->user->id, 'hash' => sha1($this->user->email)]
        );

        $this->getJson($expiredUrl)->assertStatus(403);
    }

    public function test_it_returns_404_when_user_not_found(): void
    {
        $url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => 99999, 'hash' => sha1('test@example.com')]
        );

        $this->getJson($url)->assertStatus(404);
    }

    public function test_it_returns_400_when_email_already_verified(): void
    {
        $this->user->markEmailAsVerified();

        $response = $this->getJson($this->verificationUrl);

        $this->assertApiError($response, 400, __('exceptions.'.EmailAlreadyVerifiedException::class));
    }

    public function test_it_does_not_create_new_token_when_email_already_verified(): void
    {
        $this->user->markEmailAsVerified();
        $initialCount = $this->user->tokens()->count();

        $this->getJson($this->verificationUrl);

        $this->user->refresh();
        $this->assertSame($initialCount, $this->user->tokens()->count());
    }

    public function test_it_resends_verification_email_successfully(): void
    {
        $this->assertApiSuccess(
            $this->postJson(self::EMAIL_RESEND_API, ['email' => $this->user->email]),
            200,
            __('messages.email-resend')
        );
    }

    public function test_it_returns_success_even_if_email_not_found_on_resend(): void
    {
        $this->assertApiSuccess(
            $this->postJson(self::EMAIL_RESEND_API, ['email' => 'nonexistent@example.com']),
            200,
            __('messages.email-resend')
        );
    }

    public function test_it_returns_400_when_resending_to_verified_email(): void
    {
        $this->user->markEmailAsVerified();

        $this->assertApiError(
            $this->postJson(self::EMAIL_RESEND_API, ['email' => $this->user->email]),
            400,
            __('exceptions.'.EmailAlreadyVerifiedException::class)
        );
    }

    public function test_it_validates_email_on_resend(): void
    {
        $this->postJson(self::EMAIL_RESEND_API, ['email' => 'not-an-email'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_requires_email_on_resend(): void
    {
        $this->postJson(self::EMAIL_RESEND_API, [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_rate_limits_email_resend(): void
    {
        $this->postJson(self::EMAIL_RESEND_API, ['email' => $this->user->email])->assertStatus(200);
        $this->postJson(self::EMAIL_RESEND_API, ['email' => $this->user->email])->assertStatus(429);
    }

    public function test_it_returns_token_on_successful_verification(): void
    {
        $response = $this->getJson($this->verificationUrl);

        $token = $response->json('data.token');
        $this->assertNotEmpty($token);
        $this->assertIsString($token);
        $this->assertStringContainsString('|', $token);
        $this->assertDatabaseHas('personal_access_tokens', ['tokenable_id' => $this->user->id]);
    }

    public function test_it_rejects_unsigned_verification_url(): void
    {
        $unsignedUrl = self::EMAIL_VERIFY_API.'/'.$this->user->id.'/'.sha1($this->user->email);

        $this->getJson($unsignedUrl)->assertStatus(403);
    }
}
