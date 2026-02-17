<?php

declare(strict_types=1);

namespace App\Application\Profile\Assemblers;

use App\Application\Shared\Assemblers\AbstractDTOAssembler;
use App\Domain\Profile\DTO\ChangeDeveloperEmailDTO;

/**
 * @extends AbstractDTOAssembler<ChangeDeveloperEmailDTO>
 */
class ChangeDeveloperEmailDTOAssembler extends AbstractDTOAssembler
{
    protected function getDtoClass(): string
    {
        return ChangeDeveloperEmailDTO::class;
    }
}
