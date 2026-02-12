<?php

namespace App\Exceptions\User\Auth;

use App\Exceptions\ApiException;
use App\Http\Responses\ApiResponse;

class InvalidResetTokenException extends ApiException
{
    protected $code = ApiResponse::HTTP_VALIDATION_ERROR;
}
