<?php

declare(strict_types=1);

namespace App\Application\Auth\Assemblers;

use App\Application\Shared\Assemblers\AbstractDTOAssembler;
use App\Domain\Auth\DTO\ResetPasswordDTO;

/**
 * @extends AbstractDTOAssembler<ResetPasswordDTO>
 */
class ResetPasswordDTOAssembler extends AbstractDTOAssembler
{
    protected function getDtoClass(): string
    {
        return ResetPasswordDTO::class;
    }
}
