<?php

declare(strict_types=1);

namespace App\Http\Controllers\Profile;

use App\Application\User\Profile\VerifyEmailChange;
use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

class EmailChangeVerificationController extends Controller
{
    public function verify(int $id, string $hash, VerifyEmailChange $verifyEmailChange): JsonResponse
    {
        $verifyEmailChange->run($id, $hash);
        return ApiResponse::success(__('messages.email-change-confirmed'));
    }
}
