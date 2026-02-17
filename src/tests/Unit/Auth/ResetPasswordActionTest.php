<?php

namespace Tests\Unit\Auth;

use App\Application\Shared\Exceptions\User\UserNotFoundException;
use App\Domain\Auth\Actions\CreateNewUserAction;
use App\Domain\Auth\Actions\Password\ResetPasswordAction;
use App\Domain\Auth\Actions\Password\SendPasswordResetLinkAction;
use App\Domain\Auth\Cache\PasswordResetTokensCacheInterface;
use App\Domain\Auth\DTO\CreatingUserDTO;
use App\Domain\Auth\DTO\ResetPasswordDTO;
use App\Domain\Auth\Exceptions\InvalidResetTokenException;
use App\Domain\Auth\Exceptions\PasswordResetFailedException;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Domain\Auth\Services\HashServiceInterface;
use App\Domain\Auth\Services\TokenServiceInterface;
use App\Domain\Auth\Services\TransactionServiceInterface;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ResetPasswordActionTest extends TestCase
{
    use RefreshDatabase;

    private ResetPasswordAction $action;
    private SendPasswordResetLinkAction $sendResetLinkAction;
    private PasswordResetTokensCacheInterface $cache;
    private User $user;
    private string $email = 'test@example.com';
    private string $newPassword = 'NewPassword123!';

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = app(ResetPasswordAction::class);
        $this->sendResetLinkAction = app(SendPasswordResetLinkAction::class);
        $this->cache = app(PasswordResetTokensCacheInterface::class);

        $createUserAction = app(CreateNewUserAction::class);
        $dto = new CreatingUserDTO(
            firstName: 'Иван',
            lastName: 'Петров',
            email: $this->email,
            uniqueNickname: 'reset_test',
            password: 'OldPassword123!',
            middleName: null
        );

        $this->user = $createUserAction->run($dto);
        $this->user->markEmailAsVerified();
    }

    public function test_it_resets_password_successfully(): void
    {
        $this->sendResetLinkAction->run($this->email);
        $token = $this->cache->get($this->email);

        $dto = new ResetPasswordDTO(
            email: $this->email,
            resetToken: $token,
            password: $this->newPassword
        );

        $authToken = $this->action->run($dto);

        $this->user->refresh();
        $this->assertNotEquals('OldPassword123!', $this->user->password);
        $this->assertTrue(Hash::check($this->newPassword, $this->user->password));

        $this->assertNull($this->cache->get($this->email));

        $this->assertIsString($authToken);
        $this->assertNotEmpty($authToken);
        $this->assertStringContainsString('|', $authToken);
    }

    public function test_it_deletes_all_sanctum_tokens_after_password_reset(): void
    {
        $this->sendResetLinkAction->run($this->email);
        $token = $this->cache->get($this->email);

        $this->user->createToken('device_1');
        $this->user->createToken('device_2');
        $this->assertEquals(2, $this->user->tokens()->count());

        $dto = new ResetPasswordDTO(
            email: $this->email,
            resetToken: $token,
            password: $this->newPassword
        );

        $this->action->run($dto);

        $this->assertEquals(1, $this->user->tokens()->count());
    }

    public function test_it_throws_exception_when_token_is_invalid(): void
    {
        $dto = new ResetPasswordDTO(
            email: $this->email,
            resetToken: 'invalid_token_123',
            password: $this->newPassword
        );

        $this->expectException(InvalidResetTokenException::class);
        $this->action->run($dto);
    }

    public function test_it_throws_exception_when_token_expired(): void
    {
        $this->sendResetLinkAction->run($this->email);
        $token = $this->cache->get($this->email);

        $this->cache->delete($this->email);

        $dto = new ResetPasswordDTO(
            email: $this->email,
            resetToken: $token,
            password: $this->newPassword
        );

        $this->expectException(InvalidResetTokenException::class);
        $this->action->run($dto);
    }

    public function test_it_throws_exception_when_user_not_found(): void
    {
        $nonExistentEmail = 'nonexistent@example.com';
        $this->sendResetLinkAction->run($nonExistentEmail);

        $this->cache->store($nonExistentEmail, 'some_token');
        $token = $this->cache->get($nonExistentEmail);

        $dto = new ResetPasswordDTO(
            email: $nonExistentEmail,
            resetToken: $token,
            password: $this->newPassword
        );

        $this->expectException(UserNotFoundException::class);
        $this->action->run($dto);
    }

    public function test_it_throws_exception_when_password_reset_fails(): void
    {
        $this->sendResetLinkAction->run($this->email);
        $token = $this->cache->get($this->email);

        $dto = new ResetPasswordDTO(
            email: $this->email,
            resetToken: $token,
            password: $this->newPassword
        );

        $mock = $this->createMock(UserRepositoryInterface::class);
        $mock->method('findByEmail')
            ->willThrowException(new PasswordResetFailedException());

        $this->app->instance(UserRepositoryInterface::class, $mock);

        $action = $this->app->make(ResetPasswordAction::class);

        $this->expectException(PasswordResetFailedException::class);
        $action->run($dto);
    }

    public function test_it_wraps_exception_when_update_password_fails_inside_transaction(): void
    {
        $this->sendResetLinkAction->run($this->email);
        $token = $this->cache->get($this->email);

        $dto = new ResetPasswordDTO(
            email: $this->email,
            resetToken: $token,
            password: $this->newPassword
        );

        $repo = $this->createMock(UserRepositoryInterface::class);
        $repo->method('findByEmail')->willReturn($this->user);
        $repo->method('updatePassword')->willThrowException(new \RuntimeException('db write failed'));

        $hashService = app(HashServiceInterface::class);

        $tokenService = $this->createMock(TokenServiceInterface::class);
        $transactionService = $this->createMock(TransactionServiceInterface::class);
        $transactionService->expects($this->once())
            ->method('transaction')
            ->willReturnCallback(static fn (callable $callback) => $callback());

        $action = new ResetPasswordAction(
            userRepository: $repo,
            passwordResetTokensCache: $this->cache,
            hashService: $hashService,
            tokenService: $tokenService,
            transactionService: $transactionService
        );

        try {
            $action->run($dto);
            $this->fail('Expected PasswordResetFailedException was not thrown.');
        } catch (PasswordResetFailedException $e) {
            $this->assertInstanceOf(\RuntimeException::class, $e->getPrevious());
            $this->assertSame('db write failed', $e->getPrevious()->getMessage());
        }
    }

    public function test_it_uses_transaction(): void
    {
        $this->sendResetLinkAction->run($this->email);
        $token = $this->cache->get($this->email);

        $dto = new ResetPasswordDTO(
            email: $this->email,
            resetToken: $token,
            password: $this->newPassword
        );

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(static fn (callable $callback) => $callback());

        $this->action->run($dto);
    }
}
