<?php

namespace Tests\Fakes\DTOAssembler;

use App\Application\Shared\Assemblers\AbstractDTOAssembler;

/**
 * @extends AbstractDTOAssembler<TestNoConstructorDTO>
 */
class TestNoConstructorDTOAssembler extends AbstractDTOAssembler
{
    protected function getDtoClass(): string
    {
        return TestNoConstructorDTO::class;
    }
}
