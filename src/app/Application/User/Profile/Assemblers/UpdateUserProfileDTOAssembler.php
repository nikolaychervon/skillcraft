<?php

declare(strict_types=1);

namespace App\Application\User\Profile\Assemblers;

use App\Application\Shared\Assemblers\AbstractDTOAssembler;
use App\Domain\User\Profile\DTO\UpdateUserProfileDTO;

/**
 * @extends AbstractDTOAssembler<UpdateUserProfileDTO>
 */
class UpdateUserProfileDTOAssembler extends AbstractDTOAssembler
{
    protected function getDtoClass(): string
    {
        return UpdateUserProfileDTO::class;
    }
}
