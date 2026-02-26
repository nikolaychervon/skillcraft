<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Domain\User\Auth\Actions\Email\ResendEmailAction;
use App\Domain\User\Auth\Actions\Email\VerifyEmailAction;
use App\Domain\User\Auth\RequestData\ResendEmailRequestData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResendEmailRequest;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

class EmailVerificationController extends Controller
{
    public function verify(int $id, string $hash, VerifyEmailAction $verifyEmailAction): JsonResponse
    {
        $token = $verifyEmailAction->run($id, $hash);

        return ApiResponse::success(__('messages.email-confirmed'), ['token' => $token]);
    }

    public function resend(ResendEmailRequest $request, ResendEmailAction $resendEmailAction): JsonResponse
    {
        $data = ResendEmailRequestData::fromArray($request->validated());
        $resendEmailAction->run($data);

        return ApiResponse::success(__('messages.email-resend'));
    }
}
