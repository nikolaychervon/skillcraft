<?php

declare(strict_types=1);

namespace App\Application\Shared\Exceptions\Http;

use App\Application\Shared\Constants\HttpCodesConstants;
use App\Application\Shared\Exceptions\ApiException;

class TooManyRequestsHttpException extends ApiException
{
    protected $code = HttpCodesConstants::HTTP_TOO_MANY_REQUESTS;
}
