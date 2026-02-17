<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Domain\User\Exceptions\Email\EmailAlreadyVerifiedException;
use App\Domain\User\Exceptions\Email\InvalidConfirmationLinkException;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Application\User\Auth\Assemblers\ResendEmailDTOAssembler;
use App\Domain\User\Auth\Actions\Email\ResendEmailAction;
use App\Domain\User\Auth\Actions\Email\VerifyEmailAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResendEmailRequest;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

class EmailVerificationController extends Controller
{
    public function __construct(private readonly ResendEmailDTOAssembler $resendEmailDTOAssembler)
    {
    }

    /**
     * @param int $id
     * @param string $hash
     * @param VerifyEmailAction $verifyEmailAction
     * @return JsonResponse
     *
     * @throws EmailAlreadyVerifiedException
     * @throws InvalidConfirmationLinkException
     * @throws UserNotFoundException
     */
    public function verify(int $id, string $hash, VerifyEmailAction $verifyEmailAction): JsonResponse
    {
        $token = $verifyEmailAction->run($id, $hash);

        return ApiResponse::success(
            message: __('messages.email-confirmed'),
            data: ['token' => $token]
        );
    }

    /**
     * @param ResendEmailRequest $request
     * @param ResendEmailAction $resendEmailAction
     * @return JsonResponse
     *
     * @throws EmailAlreadyVerifiedException
     */
    public function resend(ResendEmailRequest $request, ResendEmailAction $resendEmailAction): JsonResponse
    {
        $resendEmailDTO = $this->resendEmailDTOAssembler->assemble($request->validated());
        $resendEmailAction->run($resendEmailDTO);

        return ApiResponse::success(__('messages.email-resend'));
    }
}
