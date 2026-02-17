<?php

declare(strict_types=1);

namespace App\Domain\Auth\DTO;

use App\Application\Shared\DTO\BaseDTO;

readonly class CreatingUserDTO extends BaseDTO
{
    public function __construct(
        private string $firstName,
        private string $lastName,
        private string $email,
        private string $uniqueNickname,
        private string $password,
        private ?string $middleName = null,
    ) {
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUniqueNickname(): string
    {
        return $this->uniqueNickname;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
