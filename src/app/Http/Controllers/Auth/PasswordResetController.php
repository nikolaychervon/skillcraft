<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Domain\User\Auth\Actions\Password\ResetPasswordAction;
use App\Domain\User\Auth\Actions\Password\SendPasswordResetLinkAction;
use App\Domain\User\Auth\RequestData\ResetPasswordRequestData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

class PasswordResetController extends Controller
{
    public function forgot(ForgotPasswordRequest $request, SendPasswordResetLinkAction $sendPasswordResetLinkAction): JsonResponse
    {
        $sendPasswordResetLinkAction->run($request->getEmail());
        return ApiResponse::success(__('messages.password-reset-link'));
    }

    public function reset(ResetPasswordRequest $request, ResetPasswordAction $resetPasswordAction): JsonResponse
    {
        $data = ResetPasswordRequestData::fromArray($request->validated());
        $token = $resetPasswordAction->run($data);

        return ApiResponse::success(__('messages.password-reset-successful'), ['token' => $token]);
    }
}
