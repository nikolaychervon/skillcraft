<?php

declare(strict_types=1);

namespace App\Application\Profile\Assemblers;

use App\Application\Shared\Assemblers\AbstractDTOAssembler;
use App\Application\Shared\DTO\BaseDTO;
use App\Domain\Profile\DTO\UpdateDeveloperProfileDTO;

/**
 * @extends AbstractDTOAssembler<UpdateDeveloperProfileDTO>
 */
class UpdateDeveloperProfileDTOAssembler extends AbstractDTOAssembler
{
    public function assemble(array $data): BaseDTO
    {
        if (array_key_exists('middle_name', $data) && trim((string) $data['middle_name']) === '') {
            $data['middle_name'] = null;
        }

        return parent::assemble($data);
    }

    protected function getDtoClass(): string
    {
        return UpdateDeveloperProfileDTO::class;
    }
}
