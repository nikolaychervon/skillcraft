<?php

namespace App\Cache\User\Auth;

use Illuminate\Support\Facades\Cache;

class PasswordResetTokensCache
{
    private const string PREFIX = 'password_reset_';
    public const int TTL = 60;
    private const string TOKEN_FIELD = 'token';

    public function save(string $email, string $token): void
    {
        Cache::put(
            $this->getCacheKey($email),
            [self::TOKEN_FIELD => $token],
            now()->addMinutes(self::TTL)
        );
    }

    public function getTokenByEmail(string $email): ?string
    {
        $record = Cache::get($this->getCacheKey($email));
        if (!$record || !isset($record[self::TOKEN_FIELD])) return null;
        return $record[self::TOKEN_FIELD];
    }

    public function delete(string $email): void
    {
        Cache::forget($this->getCacheKey($email));
    }

    private function getCacheKey(string $email): string
    {
        return self::PREFIX . md5($email);
    }
}
