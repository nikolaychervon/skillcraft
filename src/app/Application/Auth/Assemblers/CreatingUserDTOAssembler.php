<?php

declare(strict_types=1);

namespace App\Application\Auth\Assemblers;

use App\Application\Shared\Assemblers\AbstractDTOAssembler;
use App\Domain\Auth\DTO\CreatingUserDTO;

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
