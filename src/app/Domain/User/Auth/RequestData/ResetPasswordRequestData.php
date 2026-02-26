<?php

declare(strict_types=1);

namespace App\Domain\User\Auth\RequestData;

use App\Application\Shared\RequestData\BaseRequestData;

readonly class ResetPasswordRequestData extends BaseRequestData
{
    public function __construct(
        private string $email,
        private string $resetToken,
        private string $password,
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getResetToken(): string
    {
        return $this->resetToken;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
