<?php

namespace App\Notifications\User;

use App\Cache\User\Auth\PasswordResetTokensCache;
use App\Notifications\Base\EmailNotification;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordResetNotification extends EmailNotification
{
    public function __construct(
        private readonly string $email,
        private readonly string $resetToken
    ) {
    }

    public function toMail($notifiable): MailMessage
    {
        return $this->buildMailMessage([
            'reset_url' => $this->generateResetUrl(),
            'expires_minutes' => PasswordResetTokensCache::TTL,
        ]);
    }

    protected function generateResetUrl(): string
    {
        $frontendUrl = config('app.url');
        return "$frontendUrl/reset-password?reset_token=$this->resetToken&email=$this->email";
    }
}
