<?php

declare(strict_types=1);

namespace App\Application\User\Auth\Assemblers;

use App\Application\Shared\Assemblers\AbstractDTOAssembler;
use App\Domain\User\Auth\DTO\CreatingUserDTO;

/**
 * @extends AbstractDTOAssembler<CreatingUserDTO>
 */
class CreatingUserDTOAssembler extends AbstractDTOAssembler
{
    protected function getDtoClass(): string
    {
        return CreatingUserDTO::class;
    }
}
