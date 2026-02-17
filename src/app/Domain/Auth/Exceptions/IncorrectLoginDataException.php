<?php

declare(strict_types=1);

namespace App\Domain\Auth\Exceptions;

use App\Application\Shared\Exceptions\ApiException;
use App\Http\Responses\ApiResponse;

class IncorrectLoginDataException extends ApiException
{
    protected $code = ApiResponse::HTTP_NOT_AUTHORIZED;
}
