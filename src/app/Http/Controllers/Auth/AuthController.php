<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Domain\User\Auth\Actions\LoginUserAction;
use App\Domain\User\Auth\Actions\LogoutAllUserAction;
use App\Domain\User\Auth\Actions\LogoutUserAction;
use App\Domain\User\Auth\Actions\RegisterUserAction;
use App\Domain\User\Auth\RequestData\CreatingUserRequestData;
use App\Domain\User\Auth\RequestData\LoginUserRequestData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Responses\ApiResponse;
use App\Support\Http\HttpCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request, LoginUserAction $loginUserAction): JsonResponse
    {
        $data = LoginUserRequestData::fromArray($request->validated());
        $token = $loginUserAction->run($data);

        return ApiResponse::success(__('auth.login'), ['token' => $token]);
    }

    public function register(RegisterRequest $request, RegisterUserAction $registerUserAction): JsonResponse
    {
        $data = CreatingUserRequestData::fromArray($request->validated());
        $user = $registerUserAction->run($data);

        return ApiResponse::success(__('messages.email-verify'), [
            'user_id' => $user->id,
            'email' => $user->email,
        ], HttpCode::Created);
    }

    public function logout(Request $request, LogoutUserAction $logoutUserAction): JsonResponse
    {
        $logoutUserAction->run($request->user());
        return ApiResponse::success(__('auth.logout'));
    }

    public function logoutAll(Request $request, LogoutAllUserAction $logoutAllUserAction): JsonResponse
    {
        $logoutAllUserAction->run($request->user());
        return ApiResponse::success(__('auth.logout-all'));
    }
}
