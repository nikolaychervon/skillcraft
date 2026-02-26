<?php

declare(strict_types=1);

namespace App\Http\Controllers\Profile;

use App\Domain\User\Profile\Actions\ChangeUserEmailAction;
use App\Domain\User\Profile\Actions\ChangeUserPasswordAction;
use App\Domain\User\Profile\Actions\GetUserProfileAction;
use App\Domain\User\Profile\Actions\UpdateUserProfileAction;
use App\Domain\User\Profile\RequestData\ChangeUserEmailRequestData;
use App\Domain\User\Profile\RequestData\ChangeUserPasswordRequestData;
use App\Domain\User\Profile\RequestData\UpdateUserProfileRequestData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\ChangeEmailRequest;
use App\Http\Requests\Profile\ChangePasswordRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Resources\Profile\UserProfileResource;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request, GetUserProfileAction $action): JsonResponse
    {
        $user = $action->run($request->user());
        return ApiResponse::success(data: UserProfileResource::make($user));
    }

    public function update(UpdateProfileRequest $request, UpdateUserProfileAction $action): JsonResponse
    {
        $data = UpdateUserProfileRequestData::fromArray($request->validated());
        $user = $action->run($request->user(), $data);

        return ApiResponse::success(__('messages.profile-updated'), UserProfileResource::make($user));
    }

    public function changeEmail(ChangeEmailRequest $request, ChangeUserEmailAction $action): JsonResponse
    {
        $data = ChangeUserEmailRequestData::fromArray($request->validated());
        $action->run($request->user(), $data);

        return ApiResponse::success(__('messages.email-verify'), ['email' => $data->email]);
    }

    public function changePassword(ChangePasswordRequest $request, ChangeUserPasswordAction $action): JsonResponse
    {
        $data = ChangeUserPasswordRequestData::fromArray($request->validated());
        $action->run($request->user(), $data);

        return ApiResponse::success(__('messages.password-changed'));
    }
}
