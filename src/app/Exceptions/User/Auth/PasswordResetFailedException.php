<?php

namespace App\Exceptions\User\Auth;

use App\Exceptions\ApiException;
use App\Http\Responses\ApiResponse;

class PasswordResetFailedException extends ApiException
{
    protected $code = ApiResponse::HTTP_BAD_REQUEST;
}
