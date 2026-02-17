<?php

declare(strict_types=1);

namespace App\Domain\Auth\Constants;

class AuthConstants
{
    /**
     * Название токена аутентификации по умолчанию
     */
    public const string DEFAULT_TOKEN_NAME = 'auth_token';

    /**
     * TTL токена сброса пароля в минутах
     */
    public const int PASSWORD_RESET_TOKEN_TTL = 60;

    /**
     * TTL токена верификации email в минутах
     */
    public const int EMAIL_VERIFICATION_TOKEN_TTL = 60;
}
