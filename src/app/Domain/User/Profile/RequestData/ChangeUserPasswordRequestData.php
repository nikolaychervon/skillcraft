<?php

declare(strict_types=1);

namespace App\Domain\User\Profile\RequestData;

use App\Application\Shared\RequestData\BaseRequestData;

readonly class ChangeUserPasswordRequestData extends BaseRequestData
{
    public function __construct(
        private string $oldPassword,
        private string $password,
    ) {
    }

    public function getOldPassword(): string
    {
        return $this->oldPassword;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
