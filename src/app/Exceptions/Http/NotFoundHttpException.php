<?php

namespace App\Exceptions\Http;

use App\Exceptions\ApiException;
use App\Http\Responses\ApiResponse;

class NotFoundHttpException extends ApiException
{
    protected $code = ApiResponse::HTTP_NOT_FOUND;
}
