<?php

declare(strict_types=1);

namespace App\Domain\Profile\DTO;

use App\Application\Shared\DTO\BaseDTO;

readonly class ChangeDeveloperPasswordDTO extends BaseDTO
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
