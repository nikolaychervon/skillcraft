<?php

declare(strict_types=1);

namespace App\Application\Auth\Assemblers;

use App\Application\Shared\Assemblers\AbstractDTOAssembler;
use App\Domain\Auth\DTO\LoginUserDTO;

/**
 * @extends AbstractDTOAssembler<LoginUserDTO>
 */
class LoginUserDTOAssembler extends AbstractDTOAssembler
{
    protected function getDtoClass(): string
    {
        return LoginUserDTO::class;
    }
}
