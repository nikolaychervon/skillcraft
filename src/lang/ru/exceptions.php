<?php

use App\Exceptions\Http\NotFoundHttpException;
use App\Exceptions\Http\TooManyRequestsHttpException;
use App\Exceptions\Http\UnauthorizedException;
use App\Exceptions\User\Auth\IncorrectLoginDataException;
use App\Exceptions\User\Auth\InvalidResetTokenException;
use App\Exceptions\User\Auth\PasswordResetFailedException;
use App\Exceptions\User\Email\EmailAlreadyVerifiedException;
use App\Exceptions\User\Email\InvalidConfirmationLinkException;
use App\Exceptions\User\UserNotFoundException;

return [
    IncorrectLoginDataException::class => 'Неверный email или пароль',
    NotFoundHttpException::class => 'Страница не найдена',
    UnauthorizedException::class => 'Вы не авторизованы',
    InvalidConfirmationLinkException::class => 'Неверная ссылка подтверждения',
    EmailAlreadyVerifiedException::class => 'Email уже подтверждён. Войдите в аккаунт',
    InvalidResetTokenException::class => 'Ссылка для сброса пароля недействительна или истекла',
    UserNotFoundException::class => 'Пользователь не найден',
    PasswordResetFailedException::class => 'Не удалось сбросить пароль. Пожалуйста, попробуйте снова.',
    TooManyRequestsHttpException::class => 'Слишком много частых запросов.',
];
