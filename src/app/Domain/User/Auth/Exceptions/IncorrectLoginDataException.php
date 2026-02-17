<?php

declare(strict_types=1);

namespace App\Domain\User\Auth\Exceptions;

use App\Application\Shared\Constants\HttpCodesConstants;
use App\Application\Shared\Exceptions\ApiException;

class IncorrectLoginDataException extends ApiException
{
    protected $code = HttpCodesConstants::HTTP_NOT_AUTHORIZED;
}
