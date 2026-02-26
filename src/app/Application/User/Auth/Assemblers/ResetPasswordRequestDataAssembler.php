<?php

declare(strict_types=1);

namespace App\Application\User\Auth\Assemblers;

use App\Application\Shared\Assemblers\AbstractRequestDataAssembler;
use App\Domain\User\Auth\RequestData\ResetPasswordRequestData;

/**
 * @extends AbstractRequestDataAssembler<ResetPasswordRequestData>
 */
class ResetPasswordRequestDataAssembler extends AbstractRequestDataAssembler
{
    protected function getRequestDataClass(): string
    {
        return ResetPasswordRequestData::class;
    }
}
