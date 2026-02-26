<?php

namespace Tests\Fakes\RequestDataAssembler;

use App\Application\Shared\Assemblers\AbstractRequestDataAssembler;

/**
 * @extends AbstractRequestDataAssembler<TestNoConstructorRequestData>
 */
class TestNoConstructorRequestDataAssembler extends AbstractRequestDataAssembler
{
    protected function getRequestDataClass(): string
    {
        return TestNoConstructorRequestData::class;
    }
}
