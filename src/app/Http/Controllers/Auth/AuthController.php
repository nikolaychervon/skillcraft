<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Application\Shared\Constants\HttpCodesConstants;
use App\Application\User\Auth\Assemblers\CreatingUserRequestDataAssembler;
use App\Application\User\Auth\Assemblers\LoginUserRequestDataAssembler;
use App\Domain\User\Auth\Actions\LoginUserAction;
use App\Domain\User\Auth\Actions\LogoutAllUserAction;
use App\Domain\User\Auth\Actions\LogoutUserAction;
use App\Domain\User\Auth\Actions\RegisterUserAction;
use App\Domain\User\Auth\Exceptions\IncorrectLoginDataException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly CreatingUserRequestDataAssembler $creatingUserRequestDataAssembler,
        private readonly LoginUserRequestDataAssembler $loginUserRequestDataAssembler,
    ) {
    }

    /**
     * @param LoginRequest $request
     * @param LoginUserAction $loginUserAction
     * @return JsonResponse
     *
     * @throws IncorrectLoginDataException
     */
    public function login(LoginRequest $request, LoginUserAction $loginUserAction): JsonResponse
    {
        $loginUserRequestData = $this->loginUserRequestDataAssembler->assemble($request->validated());
        $token = $loginUserAction->run($loginUserRequestData);

        return ApiResponse::success(
            message: __('auth.login'),
            data: ['token' => $token],
        );
    }

    /**
     * @param RegisterRequest $request
     * @param RegisterUserAction $registerUserAction
     * @return JsonResponse
     */
    public function register(RegisterRequest $request, RegisterUserAction $registerUserAction): JsonResponse
    {
        $creatingUserRequestData = $this->creatingUserRequestDataAssembler->assemble($request->validated());
        $user = $registerUserAction->run($creatingUserRequestData);

        return ApiResponse::success(
            message: __('messages.email-verify'),
            data: [
                'user_id' => $user->id,
                'email' => $user->email,
            ],
            code: HttpCodesConstants::HTTP_CREATED,
        );
    }

    /**
     * @param Request $request
     * @param LogoutUserAction $logoutUserAction
     * @return JsonResponse
     */
    public function logout(Request $request, LogoutUserAction $logoutUserAction): JsonResponse
    {
        $logoutUserAction->run($request->user());
        return ApiResponse::success(__('auth.logout'));
    }

    /**
     * @param Request $request
     * @param LogoutAllUserAction $logoutAllUserAction
     * @return JsonResponse
     */
    public function logoutAll(Request $request, LogoutAllUserAction $logoutAllUserAction): JsonResponse
    {
        $logoutAllUserAction->run($request->user());
        return ApiResponse::success(__('auth.logout-all'));
    }
}
