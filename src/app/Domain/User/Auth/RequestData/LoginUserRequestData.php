<?php

declare(strict_types=1);

namespace App\Domain\User\Auth\RequestData;

use App\Application\Shared\RequestData\BaseRequestData;

readonly class LoginUserRequestData extends BaseRequestData
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
