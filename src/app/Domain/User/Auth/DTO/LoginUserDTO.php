<?php

declare(strict_types=1);

namespace App\Domain\User\Auth\DTO;

use App\Application\Shared\DTO\BaseDTO;

readonly class LoginUserDTO extends BaseDTO
{
    public function __construct(
        private string $email,
        private string $password,
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
