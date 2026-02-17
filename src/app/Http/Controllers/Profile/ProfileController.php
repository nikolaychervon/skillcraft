<?php

declare(strict_types=1);

namespace App\Http\Controllers\Profile;

use App\Application\Profile\Assemblers\ChangeDeveloperEmailDTOAssembler;
use App\Application\Profile\Assemblers\ChangeDeveloperPasswordDTOAssembler;
use App\Application\Profile\Assemblers\UpdateDeveloperProfileDTOAssembler;
use App\Domain\Profile\Actions\ChangeDeveloperEmailAction;
use App\Domain\Profile\Actions\ChangeDeveloperPasswordAction;
use App\Domain\Profile\Actions\GetDeveloperProfileAction;
use App\Domain\Profile\Actions\UpdateDeveloperProfileAction;
use App\Domain\Profile\Exceptions\IncorrectCurrentPasswordException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\ChangeEmailRequest;
use App\Http\Requests\Profile\ChangePasswordRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Resources\Profile\DeveloperProfileResource;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(
        private readonly UpdateDeveloperProfileDTOAssembler $updateDeveloperProfileDTOAssembler,
        private readonly ChangeDeveloperEmailDTOAssembler $changeDeveloperEmailDTOAssembler,
        private readonly ChangeDeveloperPasswordDTOAssembler $changeDeveloperPasswordDTOAssembler,
    ) {
    }

    /**
     * @param Request $request
     * @param GetDeveloperProfileAction $action
     * @return JsonResponse
     */
    public function show(Request $request, GetDeveloperProfileAction $action): JsonResponse
    {
        $developerProfile = $action->run($request->user());
        return ApiResponse::success(data: DeveloperProfileResource::make($developerProfile));
    }

    /**
     * @param UpdateProfileRequest $request
     * @param UpdateDeveloperProfileAction $action
     * @return JsonResponse
     */
    public function update(UpdateProfileRequest $request, UpdateDeveloperProfileAction $action): JsonResponse
    {
        $dto = $this->updateDeveloperProfileDTOAssembler->assemble($request->validated());
        $developerProfile = $action->run($request->user(), $dto);

        return ApiResponse::success(
            message: __('messages.profile-updated'),
            data: DeveloperProfileResource::make($developerProfile),
        );
    }

    /**
     * @param ChangeEmailRequest $request
     * @param ChangeDeveloperEmailAction $action
     * @return JsonResponse
     */
    public function changeEmail(ChangeEmailRequest $request, ChangeDeveloperEmailAction $action): JsonResponse
    {
        $developerProfile = $request->user();
        $dto = $this->changeDeveloperEmailDTOAssembler->assemble($request->validated());
        $action->run($developerProfile, $dto);

        return ApiResponse::success(
            message: __('messages.email-verify'),
            data: ['email' => $dto->getEmail()],
        );
    }

    /**
     * @param ChangePasswordRequest $request
     * @param ChangeDeveloperPasswordAction $action
     * @return JsonResponse
     *
     * @throws IncorrectCurrentPasswordException
     */
    public function changePassword(ChangePasswordRequest $request, ChangeDeveloperPasswordAction $action): JsonResponse
    {
        $dto = $this->changeDeveloperPasswordDTOAssembler->assemble($request->validated());
        $action->run($request->user(), $dto);

        return ApiResponse::success(message: __('messages.password-changed'));
    }
}
