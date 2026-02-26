<?php

declare(strict_types=1);

namespace App\Application\User\Auth\Assemblers;

use App\Application\Shared\Assemblers\AbstractRequestDataAssembler;
use App\Domain\User\Auth\RequestData\CreatingUserRequestData;

/**
 * @extends AbstractRequestDataAssembler<CreatingUserRequestData>
 */
class CreatingUserRequestDataAssembler extends AbstractRequestDataAssembler
{
    protected function getRequestDataClass(): string
    {
        return CreatingUserRequestData::class;
    }
}
