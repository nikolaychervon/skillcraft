<?php

namespace App\Exceptions\User\Email;

use App\Exceptions\ApiException;
use App\Http\Responses\ApiResponse;

class EmailAlreadyVerifiedException extends ApiException
{
    protected $code = ApiResponse::HTTP_BAD_REQUEST;
}
