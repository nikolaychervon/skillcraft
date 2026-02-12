<?php

namespace App\Exceptions\Http;

use App\Exceptions\ApiException;
use App\Http\Responses\ApiResponse;

class TooManyRequestsHttpException extends ApiException
{
    protected $code = ApiResponse::HTTP_TOO_MANY_REQUESTS;
}
