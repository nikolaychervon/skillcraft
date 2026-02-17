<?php

declare(strict_types=1);

namespace App\Application\Auth\Assemblers;

use App\Application\Shared\Assemblers\AbstractDTOAssembler;
use App\Domain\Auth\DTO\ResendEmailDTO;

/**
 * @extends AbstractDTOAssembler<ResendEmailDTO>
 */
class ResendEmailDTOAssembler extends AbstractDTOAssembler
{
    protected function getDtoClass(): string
    {
        return ResendEmailDTO::class;
    }
}
