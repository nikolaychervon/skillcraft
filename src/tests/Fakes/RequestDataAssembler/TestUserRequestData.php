<?php

namespace Tests\Fakes\RequestDataAssembler;

use App\Application\Shared\RequestData\BaseRequestData;

readonly class TestUserRequestData extends BaseRequestData
{
    public function __construct(
        private string $firstName,
        private string $lastName,
        private ?string $middleName = null,
    ) {}

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function middleName(): ?string
    {
        return $this->middleName;
    }
}
