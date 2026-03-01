<?php

declare(strict_types=1);

namespace App\Http\Controllers\Mentor;

use App\Application\Mentor\CreateNewMentor;
use App\Application\Mentor\GetUserMentors;
use App\Domain\Mentor\RequestData\CreateNewMentorRequestData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Base\AuthenticatedRequest;
use App\Http\Requests\Mentor\StoreMentorRequest;
use App\Http\Resources\Mentor\MentorItemResource;
use App\Http\Resources\Mentor\MentorResource;
use App\Http\Responses\ApiResponse;
use App\Models\Mentor;
use App\Support\Http\HttpCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class MentorController extends Controller
{
    public function index(AuthenticatedRequest $request, GetUserMentors $getUserMentors): JsonResponse
    {
        $mentors = $getUserMentors->run($request->getDomainUser()->id);

        return ApiResponse::success(data: MentorItemResource::collection($mentors));
    }

    public function store(StoreMentorRequest $request, CreateNewMentor $createNewMentor): JsonResponse
    {
        $requestData = CreateNewMentorRequestData::fromArray($request->validated());
        $mentor = $createNewMentor->run($requestData, $request->getDomainUser()->id);

        return ApiResponse::success(
            message: __('messages.mentor-created'),
            data: MentorResource::make($mentor),
            code: HttpCode::Created,
        );
    }

    public function show(Mentor $mentor): JsonResponse
    {
        return response()->json([]);
    }

    public function update(Request $request, Mentor $mentor): JsonResponse
    {
        return response()->json([]);
    }

    public function destroy(Mentor $mentor): JsonResponse
    {
        return response()->json([]);
    }
}
