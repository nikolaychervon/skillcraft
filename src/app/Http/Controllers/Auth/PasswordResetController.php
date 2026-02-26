<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Domain\User\Exceptions\UserNotFoundException;
use App\Application\User\Auth\Assemblers\ResetPasswordRequestDataAssembler;
use App\Domain\User\Auth\Actions\Password\ResetPasswordAction;
use App\Domain\User\Auth\Actions\Password\SendPasswordResetLinkAction;
use App\Domain\User\Auth\Exceptions\InvalidResetTokenException;
use App\Domain\User\Auth\Exceptions\PasswordResetFailedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

class PasswordResetController extends Controller
{
    public function __construct(private readonly ResetPasswordRequestDataAssembler $resetPasswordRequestDataAssembler)
    {
    }

    /**
     * @param ForgotPasswordRequest $request
     * @param SendPasswordResetLinkAction $sendPasswordResetLinkAction
     * @return JsonResponse
     */
    public function forgot(
        ForgotPasswordRequest $request,
        SendPasswordResetLinkAction $sendPasswordResetLinkAction
    ): JsonResponse {
        $sendPasswordResetLinkAction->run($request->getEmail());
        return ApiResponse::success(__('messages.password-reset-link'));
    }

    /**
     * @param ResetPasswordRequest $request
     * @param ResetPasswordAction $resetPasswordAction
     * @return JsonResponse
     *
     * @throws InvalidResetTokenException
     * @throws PasswordResetFailedException
     * @throws UserNotFoundException
     */
    public function reset(ResetPasswordRequest $request, ResetPasswordAction $resetPasswordAction): JsonResponse
    {
        $resetPasswordRequestData = $this->resetPasswordRequestDataAssembler->assemble($request->validated());
        $token = $resetPasswordAction->run($resetPasswordRequestData);

        return ApiResponse::success(
            message: __('messages.password-reset-successful'),
            data: ['token' => $token],
        );
    }
}
