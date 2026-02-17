<?php

declare(strict_types=1);

namespace App\Domain\User\Profile\Exceptions;

use App\Application\Shared\Constants\HttpCodesConstants;
use App\Application\Shared\Exceptions\ApiException;

class IncorrectCurrentPasswordException extends ApiException
{
    protected $code = HttpCodesConstants::HTTP_VALIDATION_ERROR;
}
