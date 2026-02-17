<?php

namespace App\Http\Responses;

use App\Application\Shared\Constants\HttpCodesConstants;
use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * @param string $message
     * @param mixed|null $data
     * @param int $code
     * @return JsonResponse
     */
    public static function success(
        string $message = 'Success',
        mixed $data = null,
        int $code = HttpCodesConstants::HTTP_OK
    ) : JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * @param string $message
     * @param int $code
     * @param mixed|null $errors
     * @return JsonResponse
     */
    public static function error(
        string $message = 'Error',
        int $code = HttpCodesConstants::HTTP_BAD_REQUEST,
        mixed $errors = null
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!is_null($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * @param mixed|null $errors
     * @param string $message
     * @return JsonResponse
     */
    public static function validationError(mixed $errors = null, string $message = 'Validation Error'): JsonResponse
    {
        return self::error($message, HttpCodesConstants::HTTP_VALIDATION_ERROR, $errors);
    }
}
