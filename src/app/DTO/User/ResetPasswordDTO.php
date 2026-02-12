<?php

namespace App\DTO\User;

readonly class ResetPasswordDTO
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
