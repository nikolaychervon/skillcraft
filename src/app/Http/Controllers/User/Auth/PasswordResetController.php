<?php

namespace App\Http\Controllers\User\Auth;

use App\Actions\User\Password\ResetPasswordAction;
use App\Actions\User\Password\SendPasswordResetLinkAction;
use App\DTO\User\ResetPasswordDTO;
use App\Exceptions\User\Auth\InvalidResetTokenException;
use App\Exceptions\User\Auth\PasswordResetFailedException;
use App\Exceptions\User\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\ForgotPasswordRequest;
use App\Http\Requests\User\Auth\ResetPasswordRequest;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

class PasswordResetController extends Controller
{
    public function __construct(
        private SendPasswordResetLinkAction $sendPasswordResetLinkAction,
        private ResetPasswordAction $resetPasswordAction,
    ) {
    }

    public function forgot(ForgotPasswordRequest $request): JsonResponse
    {
        $this->sendPasswordResetLinkAction->run($request->getEmail());
        return ApiResponse::success(__('messages.password-reset-link'));
    }

    /**
     * @throws InvalidResetTokenException
     * @throws PasswordResetFailedException
     * @throws UserNotFoundException
     */
    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        $resetPasswordDTO = new ResetPasswordDTO(
            email: $request->getEmail(),
            resetToken: $request->getResetToken(),
            password: $request->getUserPassword()
        );

        $token = $this->resetPasswordAction->run($resetPasswordDTO);
        return ApiResponse::success(
            message: __('messages.password-reset-successful'),
            data: ['token' => $token],
        );
    }
}
