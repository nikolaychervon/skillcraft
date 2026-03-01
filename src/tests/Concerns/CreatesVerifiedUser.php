<?php

declare(strict_types=1);

namespace Tests\Concerns;

use App\Application\User\Auth\CreateNewUser;
use App\Domain\User\Auth\RequestData\CreatingUserRequestData;
use App\Models\User;

trait CreatesVerifiedUser
{
    private const string DEFAULT_PASSWORD = 'Password123!';

    /** @param array{email?: string, password?: string, first_name?: string, last_name?: string, unique_nickname?: string, middle_name?: string|null} $overrides */
    protected function createVerifiedUser(array $overrides = []): User
    {
        $attrs = array_merge([
            'first_name' => 'Иван',
            'last_name' => 'Петров',
            'email' => 'user@example.com',
            'unique_nickname' => 'test_user_'.uniqid(),
            'password' => self::DEFAULT_PASSWORD,
            'middle_name' => null,
        ], $overrides);

        $requestData = new CreatingUserRequestData(
            firstName: $attrs['first_name'],
            lastName: $attrs['last_name'],
            email: $attrs['email'],
            uniqueNickname: $attrs['unique_nickname'],
            password: $attrs['password'],
            middleName: $attrs['middle_name'],
        );

        $domainUser = app(CreateNewUser::class)->run($requestData);
        $user = User::query()->findOrFail($domainUser->id);
        $user->markEmailAsVerified();

        return $user;
    }

    /** @param array{email?: string, password?: string, first_name?: string, last_name?: string, unique_nickname?: string, middle_name?: string|null} $overrides */
    protected function createUnverifiedUser(array $overrides = []): User
    {
        $attrs = array_merge([
            'first_name' => 'Иван',
            'last_name' => 'Петров',
            'email' => 'unverified@example.com',
            'unique_nickname' => 'unverified_'.uniqid(),
            'password' => self::DEFAULT_PASSWORD,
            'middle_name' => null,
        ], $overrides);

        $requestData = new CreatingUserRequestData(
            firstName: $attrs['first_name'],
            lastName: $attrs['last_name'],
            email: $attrs['email'],
            uniqueNickname: $attrs['unique_nickname'],
            password: $attrs['password'],
            middleName: $attrs['middle_name'],
        );

        $domainUser = app(CreateNewUser::class)->run($requestData);

        return User::query()->findOrFail($domainUser->id);
    }

    protected function defaultPassword(): string
    {
        return self::DEFAULT_PASSWORD;
    }
}
