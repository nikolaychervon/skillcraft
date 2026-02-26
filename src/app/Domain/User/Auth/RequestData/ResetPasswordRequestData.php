<?php

declare(strict_types=1);

namespace App\Domain\User\Auth\RequestData;

use App\Application\Shared\RequestData\BaseRequestData;

final readonly class ResetPasswordRequestData extends BaseRequestData
{
    public function __construct(
        public string $email,
        public string $resetToken,
        public string $password,
    ) {}
}
