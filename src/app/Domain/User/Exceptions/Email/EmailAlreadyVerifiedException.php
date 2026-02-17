<?php

declare(strict_types=1);

namespace App\Domain\User\Exceptions\Email;

use App\Application\Shared\Constants\HttpCodesConstants;
use App\Application\Shared\Exceptions\ApiException;

class EmailAlreadyVerifiedException extends ApiException
{
    protected $code = HttpCodesConstants::HTTP_BAD_REQUEST;
}
