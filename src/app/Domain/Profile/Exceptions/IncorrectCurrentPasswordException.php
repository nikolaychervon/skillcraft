<?php

declare(strict_types=1);

namespace App\Domain\Profile\Exceptions;

use App\Application\Shared\Exceptions\ApiException;
use App\Http\Responses\ApiResponse;

class IncorrectCurrentPasswordException extends ApiException
{
    protected $code = ApiResponse::HTTP_VALIDATION_ERROR;
}
