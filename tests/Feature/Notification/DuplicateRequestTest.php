<?php

namespace Tests\Feature\Notification;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DuplicateRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_duplicate_request_is_blocked(): void
    {
        $payload = [
            'channel' => 'email',
            'message' => 'Duplicate',
            'priority' => 'high',
            'recipients' => [
                'duplicate@gmail.com',
            ],
            'idempotency_key' => 'duplicate-key',
        ];

        $this->postJson('/api/v1/notifications', $payload);

        $response = $this->postJson(
            '/api/v1/notifications',
            $payload
        );

        $response->assertStatus(409);
    }
}
