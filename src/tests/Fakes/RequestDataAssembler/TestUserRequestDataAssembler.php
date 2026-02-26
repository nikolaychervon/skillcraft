<?php

namespace Tests\Fakes\RequestDataAssembler;

use App\Application\Shared\Assemblers\AbstractRequestDataAssembler;

/**
 * @extends AbstractRequestDataAssembler<TestUserRequestData>
 */
class TestUserRequestDataAssembler extends AbstractRequestDataAssembler
{
    protected function getRequestDataClass(): string
    {
        return TestUserRequestData::class;
    }
}
