<?php

declare(strict_types=1);

namespace App\Domain\Profile\DTO;

use App\Application\Shared\DTO\BaseDTO;

readonly class ChangeDeveloperEmailDTO extends BaseDTO
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
