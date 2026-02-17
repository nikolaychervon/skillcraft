<?php

declare(strict_types=1);

namespace App\Domain\Profile\DTO;

use App\Application\Shared\DTO\BaseDTO;

readonly class UpdateDeveloperProfileDTO extends BaseDTO
{
    public function __construct(
        private ?string $firstName = null,
        private ?string $lastName = null,
        private ?string $middleName = null,
        private ?string $uniqueNickname = null,
    ) {
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function getUniqueNickname(): ?string
    {
        return $this->uniqueNickname;
    }
}
