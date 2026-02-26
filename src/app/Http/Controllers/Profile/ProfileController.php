<?php

declare(strict_types=1);

namespace App\Http\Controllers\Profile;

use App\Application\User\Profile\Assemblers\ChangeUserEmailRequestDataAssembler;
use App\Application\User\Profile\Assemblers\ChangeUserPasswordRequestDataAssembler;
use App\Application\User\Profile\Assemblers\UpdateUserProfileRequestDataAssembler;
use App\Domain\User\Profile\Actions\ChangeUserEmailAction;
use App\Domain\User\Profile\Actions\ChangeUserPasswordAction;
use App\Domain\User\Profile\Actions\GetUserProfileAction;
use App\Domain\User\Profile\Actions\UpdateUserProfileAction;
use App\Domain\User\Profile\Exceptions\IncorrectCurrentPasswordException;
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
    public function __construct(
        private readonly UpdateUserProfileRequestDataAssembler $updateUserProfileRequestDataAssembler,
        private readonly ChangeUserEmailRequestDataAssembler $changeUserEmailRequestDataAssembler,
        private readonly ChangeUserPasswordRequestDataAssembler $changeUserPasswordRequestDataAssembler,
    ) {
    }

    public function show(Request $request, GetUserProfileAction $action): JsonResponse
    {
        $user = $action->run($request->user());
        return ApiResponse::success(data: UserProfileResource::make($user));
    }

    public function update(UpdateProfileRequest $request, UpdateUserProfileAction $action): JsonResponse
    {
        $requestData = $this->updateUserProfileRequestDataAssembler->assemble($request->validated());
        $user = $action->run($request->user(), $requestData);

        return ApiResponse::success(
            message: __('messages.profile-updated'),
            data: UserProfileResource::make($user),
        );
    }

    public function changeEmail(ChangeEmailRequest $request, ChangeUserEmailAction $action): JsonResponse
    {
        $user = $request->user();
        $requestData = $this->changeUserEmailRequestDataAssembler->assemble($request->validated());
        $action->run($user, $requestData);

        return ApiResponse::success(
            message: __('messages.email-verify'),
            data: ['email' => $requestData->getEmail()],
        );
    }

    /**
     * @throws IncorrectCurrentPasswordException
     */
    public function changePassword(ChangePasswordRequest $request, ChangeUserPasswordAction $action): JsonResponse
    {
        $requestData = $this->changeUserPasswordRequestDataAssembler->assemble($request->validated());
        $action->run($request->user(), $requestData);

        return ApiResponse::success(message: __('messages.password-changed'));
    }
}
