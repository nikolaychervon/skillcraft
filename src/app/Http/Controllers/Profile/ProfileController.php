<?php

declare(strict_types=1);

namespace App\Http\Controllers\Profile;

use App\Application\User\Profile\Assemblers\ChangeUserEmailDTOAssembler;
use App\Application\User\Profile\Assemblers\ChangeUserPasswordDTOAssembler;
use App\Application\User\Profile\Assemblers\UpdateUserProfileDTOAssembler;
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
        private readonly UpdateUserProfileDTOAssembler $updateUserProfileDTOAssembler,
        private readonly ChangeUserEmailDTOAssembler $changeUserEmailDTOAssembler,
        private readonly ChangeUserPasswordDTOAssembler $changeUserPasswordDTOAssembler,
    ) {
    }

    public function show(Request $request, GetUserProfileAction $action): JsonResponse
    {
        $user = $action->run($request->user());
        return ApiResponse::success(data: UserProfileResource::make($user));
    }

    public function update(UpdateProfileRequest $request, UpdateUserProfileAction $action): JsonResponse
    {
        $dto = $this->updateUserProfileDTOAssembler->assemble($request->validated());
        $user = $action->run($request->user(), $dto);

        return ApiResponse::success(
            message: __('messages.profile-updated'),
            data: UserProfileResource::make($user),
        );
    }

    public function changeEmail(ChangeEmailRequest $request, ChangeUserEmailAction $action): JsonResponse
    {
        $user = $request->user();
        $dto = $this->changeUserEmailDTOAssembler->assemble($request->validated());
        $action->run($user, $dto);

        return ApiResponse::success(
            message: __('messages.email-verify'),
            data: ['email' => $dto->getEmail()],
        );
    }

    /**
     * @throws IncorrectCurrentPasswordException
     */
    public function changePassword(ChangePasswordRequest $request, ChangeUserPasswordAction $action): JsonResponse
    {
        $dto = $this->changeUserPasswordDTOAssembler->assemble($request->validated());
        $action->run($request->user(), $dto);

        return ApiResponse::success(message: __('messages.password-changed'));
    }
}
