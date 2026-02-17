<?php

use App\Infrastructure\Notifications\Auth\PasswordResetNotification;
use App\Infrastructure\Notifications\Auth\VerifyEmailForRegisterNotification;
use App\Infrastructure\Notifications\Profile\VerifyEmailChangeNotification;

return [
    VerifyEmailForRegisterNotification::class => [
        'subject' => 'GradeUP registration confirmation',
        'greeting' => 'Hello, :name!',
        'lines_1' => [
            'You received this email because you registered on the GradeUP platform.',
            'To activate your account, please confirm your email:',
        ],
        'action' => [
            'text' => 'Confirm email',
            'url' => ':verification_url',
        ],
        'lines_2' => [
            'The link is valid for 60 minutes.',
            'If you haven\'t registered, please ignore this email.',
        ],
    ],
    PasswordResetNotification::class => [
        'subject' => 'Resetting your GradeUP password',
        'greeting' => 'Hello!',
        'lines_1' => [
            'You are receiving this email because we have received a request to reset the password for your account.',
        ],
        'action' => [
            'text' => 'Reset password',
            'url' => ':reset_url',
        ],
        'lines_2' => [
            'The link is valid for :expires_minutes minutes.',
            'If you did not request a password reset, simply ignore this email.',
        ],
    ],
    VerifyEmailChangeNotification::class => [
        'subject' => 'GradeUP email change confirmation',
        'greeting' => 'Hello, :name!',
        'lines_1' => [
            'You requested to change the email address for your GradeUP account.',
            'Please confirm your new email (:pending_email) by clicking the link below:',
        ],
        'action' => [
            'text' => 'Confirm new email',
            'url' => ':verification_url',
        ],
        'lines_2' => [
            'The link is valid for 60 minutes.',
            'If you did not request an email change, please ignore this email.',
        ],
    ],
];
