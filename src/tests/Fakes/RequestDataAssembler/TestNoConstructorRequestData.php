<?php

declare(strict_types=1);

namespace Tests\Fakes\RequestDataAssembler;

use App\Domain\Shared\RequestData\BaseRequestData;

readonly class TestNoConstructorRequestData extends BaseRequestData
{
    // Intentionally no constructor
}
