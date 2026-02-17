<?php

use App\Infrastructure\Notifications\Auth\PasswordResetNotification;
use App\Infrastructure\Notifications\Auth\VerifyEmailForRegisterNotification;
use App\Infrastructure\Notifications\Profile\VerifyEmailChangeNotification;

return [
    VerifyEmailForRegisterNotification::class => [
        'subject' => 'Подтверждение регистрации на GradeUP',
        'greeting' => 'Здравствуйте, :name!',
        'lines_1' => [
            'Вы получили это письмо, потому что зарегистрировались на платформе GradeUP.',
            'Для активации аккаунта подтвердите ваш email:',
        ],
        'action' => [
            'text' => 'Подтвердить email',
            'url' => ':verification_url',
        ],
        'lines_2' => [
            'Ссылка действительна в течение 60 минут.',
            'Если вы не регистрировались, просто проигнорируйте это письмо.',
        ],
    ],
    PasswordResetNotification::class => [
        'subject' => 'Сброс пароля на GradeUP',
        'greeting' => 'Здравствуйте!',
        'lines_1' => [
            'Вы получили это письмо, потому что мы получили запрос на сброс пароля для вашей учётной записи.',
        ],
        'action' => [
            'text' => 'Сбросить пароль',
            'url' => ':reset_url',
        ],
        'lines_2' => [
            'Ссылка действительна в течение :expires_minutes минут.',
            'Если вы не запрашивали сброс пароля, просто проигнорируйте это письмо.',
        ],
    ],
    VerifyEmailChangeNotification::class => [
        'subject' => 'Подтверждение смены email на GradeUP',
        'greeting' => 'Здравствуйте, :name!',
        'lines_1' => [
            'Вы запросили смену email для вашей учётной записи на GradeUP.',
            'Подтвердите новый email (:pending_email), перейдя по ссылке:',
        ],
        'action' => [
            'text' => 'Подтвердить новый email',
            'url' => ':verification_url',
        ],
        'lines_2' => [
            'Ссылка действительна в течение 60 минут.',
            'Если вы не запрашивали смену email — просто проигнорируйте это письмо.',
        ],
    ],
];
