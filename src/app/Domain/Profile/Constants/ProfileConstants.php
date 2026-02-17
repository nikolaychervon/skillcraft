<?php

declare(strict_types=1);

namespace App\Domain\Profile\Constants;

class ProfileConstants
{
    /**
     * TTL ссылки подтверждения смены email в минутах
     */
    public const int EMAIL_CHANGE_VERIFICATION_TTL = 60;
}
