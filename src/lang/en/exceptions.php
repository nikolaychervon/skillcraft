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
    IncorrectLoginDataException::class => 'Incorrect email address or password',
    NotFoundHttpException::class => 'Page not found',
    UnauthorizedException::class => 'Unauthorized',
    InvalidConfirmationLinkException::class => 'Invalid confirmation link',
    EmailAlreadyVerifiedException::class => 'Your email has already been confirmed. Log in to your account',
    InvalidResetTokenException::class => 'The password reset link is invalid or expired.',
    UserNotFoundException::class => 'User not found',
    PasswordResetFailedException::class => 'Failed to reset your password. Please try again.',
    TooManyRequestsHttpException::class => 'Too Many Attempts.',
];
