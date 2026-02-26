<?php

declare(strict_types=1);

namespace App\Application\User\Profile\Assemblers;

use App\Application\Shared\Assemblers\AbstractRequestDataAssembler;
use App\Domain\User\Profile\RequestData\UpdateUserProfileRequestData;

/**
 * @extends AbstractRequestDataAssembler<UpdateUserProfileRequestData>
 */
class UpdateUserProfileRequestDataAssembler extends AbstractRequestDataAssembler
{
    protected function getRequestDataClass(): string
    {
        return UpdateUserProfileRequestData::class;
    }
}
