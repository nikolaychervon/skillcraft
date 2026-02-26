<?php

declare(strict_types=1);

namespace App\Application\User\Profile\Assemblers;

use App\Application\Shared\Assemblers\AbstractRequestDataAssembler;
use App\Domain\User\Profile\RequestData\ChangeUserEmailRequestData;

/**
 * @extends AbstractRequestDataAssembler<ChangeUserEmailRequestData>
 */
class ChangeUserEmailRequestDataAssembler extends AbstractRequestDataAssembler
{
    protected function getRequestDataClass(): string
    {
        return ChangeUserEmailRequestData::class;
    }
}
