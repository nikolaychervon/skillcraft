<?php

declare(strict_types=1);

namespace Tests\Concerns;

use Illuminate\Testing\TestResponse;

trait ApiAssertions
{
    protected function assertApiSuccess(TestResponse $response, int $status = 200, ?string $message = null): void
    {
        $response->assertStatus($status)->assertJsonPath('success', true);
        if ($message !== null) {
            $response->assertJsonPath('message', $message);
        }
    }

    protected function assertApiError(TestResponse $response, int $status, ?string $message = null): void
    {
        $response->assertStatus($status)->assertJsonPath('success', false);
        if ($message !== null) {
            $response->assertJsonPath('message', $message);
        }
    }
}
