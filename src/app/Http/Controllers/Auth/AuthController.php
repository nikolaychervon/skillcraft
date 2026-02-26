<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Application\User\Auth\LoginUser;
use App\Application\User\Auth\LogoutAllUser;
use App\Application\User\Auth\LogoutUser;
use App\Application\User\Auth\RegisterUser;
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
    public function login(LoginRequest $request, LoginUser $loginUser): JsonResponse
    {
        $data = LoginUserRequestData::fromArray($request->validated());
        $token = $loginUser->run($data);

        return ApiResponse::success(__('auth.login'), ['token' => $token]);
    }

    public function register(RegisterRequest $request, RegisterUser $registerUser): JsonResponse
    {
        $data = CreatingUserRequestData::fromArray($request->validated());
        $user = $registerUser->run($data);

        return ApiResponse::success(__('messages.email-verify'), [
            'user_id' => $user->id,
            'email' => $user->email,
        ], HttpCode::Created);
    }

    public function logout(Request $request, LogoutUser $logoutUser): JsonResponse
    {
        $logoutUser->run($request->user());
        return ApiResponse::success(__('auth.logout'));
    }

    public function logoutAll(Request $request, LogoutAllUser $logoutAllUser): JsonResponse
    {
        $logoutAllUser->run($request->user());
        return ApiResponse::success(__('auth.logout-all'));
    }
}
