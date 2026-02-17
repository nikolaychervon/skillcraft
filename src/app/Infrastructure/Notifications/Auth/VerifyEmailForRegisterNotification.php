<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifications\Auth;

use App\Domain\User\Auth\Constants\AuthConstants;
use App\Infrastructure\Notifications\Base\EmailNotification;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class VerifyEmailForRegisterNotification extends EmailNotification
{
    public function toMail(User $notifiable): MailMessage
    {
        $verificationUrl = $this->generateVerificationUrl($notifiable);

        return $this->buildMailMessage([
            'name' => $notifiable->first_name,
            'verification_url' => $verificationUrl,
        ]);
    }

    protected function generateVerificationUrl(User $notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(AuthConstants::EMAIL_VERIFICATION_TOKEN_TTL),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}
