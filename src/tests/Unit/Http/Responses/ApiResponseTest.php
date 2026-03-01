<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Responses;

use App\Http\Responses\ApiResponse;
use App\Support\Http\HttpCode;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class ApiResponseTest extends TestCase
{
    public function test_success_returns_json_with_default_message_and_code(): void
    {
        $response = ApiResponse::success();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());

        $payload = $response->getData(true);
        $this->assertTrue($payload['success']);
        $this->assertSame('Success', $payload['message']);
        $this->assertNull($payload['data']);
    }

    public function test_success_with_data_and_custom_message_and_code(): void
    {
        $response = ApiResponse::success('Created', ['id' => 1], HttpCode::Created);

        $this->assertSame(201, $response->getStatusCode());

        $payload = $response->getData(true);
        $this->assertTrue($payload['success']);
        $this->assertSame('Created', $payload['message']);
        $this->assertSame(['id' => 1], $payload['data']);
    }

    public function test_success_accepts_int_status_code(): void
    {
        $response = ApiResponse::success('OK', null, 200);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_error_returns_json_with_false_success(): void
    {
        $response = ApiResponse::error('Something failed', HttpCode::BadRequest);

        $this->assertSame(400, $response->getStatusCode());

        $payload = $response->getData(true);
        $this->assertFalse($payload['success']);
        $this->assertSame('Something failed', $payload['message']);
        $this->assertArrayNotHasKey('errors', $payload);
    }

    public function test_error_with_errors_payload(): void
    {
        $errors = ['field' => ['First error']];
        $response = ApiResponse::error('Validation failed', HttpCode::ValidationError, $errors);

        $this->assertSame(422, $response->getStatusCode());

        $payload = $response->getData(true);
        $this->assertFalse($payload['success']);
        $this->assertSame('Validation failed', $payload['message']);
        $this->assertSame($errors, $payload['errors']);
    }

    public function test_validation_error_returns_422_with_message(): void
    {
        $response = ApiResponse::validationError(['email' => 'Required'], 'Invalid input');

        $this->assertSame(422, $response->getStatusCode());

        $payload = $response->getData(true);
        $this->assertFalse($payload['success']);
        $this->assertSame('Invalid input', $payload['message']);
        $this->assertSame(['email' => 'Required'], $payload['errors']);
    }

    public function test_validation_error_default_message(): void
    {
        $response = ApiResponse::validationError();

        $payload = $response->getData(true);
        $this->assertSame('Validation Error', $payload['message']);
    }
}
