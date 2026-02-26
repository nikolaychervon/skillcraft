<?php

declare(strict_types=1);

namespace Tests\Fakes\RequestDataAssembler;

use App\Application\Shared\RequestData\BaseRequestData;

final readonly class TestUserRequestData extends BaseRequestData
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public ?string $middleName = null,
    ) {}
}
