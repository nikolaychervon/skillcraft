<?php

namespace App\Http\Controllers\User\Auth;

use App\Actions\User\Email\ResendEmailAction;
use App\Actions\User\Email\VerifyEmailAction;
use App\Exceptions\User\Email\EmailAlreadyVerifiedException;
use App\Exceptions\User\Email\InvalidConfirmationLinkException;
use App\Exceptions\User\UserNotFoundException;
use App\Http\Requests\User\ResendEmailRequest;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

class EmailVerificationController
{
    public function __construct(
        private VerifyEmailAction $verifyEmailAction,
        private ResendEmailAction $resendEmailAction,
    ) {
    }

    /**
     * @throws InvalidConfirmationLinkException
     * @throws EmailAlreadyVerifiedException
     * @throws UserNotFoundException
     */
    public function verify(int $id, string $hash): JsonResponse
    {
        $token = $this->verifyEmailAction->run($id, $hash);

        return ApiResponse::success(
            message: __('messages.email-confirmed'),
            data: ['token' => $token]
        );
    }

    /**
     * @throws EmailAlreadyVerifiedException
     */
    public function resend(ResendEmailRequest $request): JsonResponse
    {
        $this->resendEmailAction->run($request->getEmail());
        return ApiResponse::success(__('messages.email-resend'));
    }
}
