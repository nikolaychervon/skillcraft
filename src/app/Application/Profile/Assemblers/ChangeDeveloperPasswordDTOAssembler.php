<?php

declare(strict_types=1);

namespace App\Application\Profile\Assemblers;

use App\Application\Shared\Assemblers\AbstractDTOAssembler;
use App\Domain\Profile\DTO\ChangeDeveloperPasswordDTO;

/**
 * @extends AbstractDTOAssembler<ChangeDeveloperPasswordDTO>
 */
class ChangeDeveloperPasswordDTOAssembler extends AbstractDTOAssembler
{
    protected function getDtoClass(): string
    {
        return ChangeDeveloperPasswordDTO::class;
    }
}
