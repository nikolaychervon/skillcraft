<?php

namespace App\Exceptions\User\Auth;

use App\Exceptions\ApiException;
use App\Http\Responses\ApiResponse;

class IncorrectLoginDataException extends ApiException
{
    protected $code = ApiResponse::HTTP_NOT_AUTHORIZED;
}
