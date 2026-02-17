<?php

declare(strict_types=1);

namespace App\Application\User\Profile\Assemblers;

use App\Application\Shared\Assemblers\AbstractDTOAssembler;
use App\Domain\User\Profile\DTO\ChangeUserEmailDTO;

/**
 * @extends AbstractDTOAssembler<ChangeUserEmailDTO>
 */
class ChangeUserEmailDTOAssembler extends AbstractDTOAssembler
{
    protected function getDtoClass(): string
    {
        return ChangeUserEmailDTO::class;
    }
}
