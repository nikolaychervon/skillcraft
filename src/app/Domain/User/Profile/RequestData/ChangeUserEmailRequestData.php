<?php

declare(strict_types=1);

namespace App\Domain\User\Profile\RequestData;

use App\Application\Shared\RequestData\BaseRequestData;

readonly class ChangeUserEmailRequestData extends BaseRequestData
{
    public function __construct(
        private string $email,
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
