<?php

namespace Tests\Feature\Notification;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class NotificationCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_can_be_created(): void
    {
        Queue::fake();
        $response = $this->postJson(
            '/api/v1/notifications',
            [
                'channel' => 'email',
                'message' => 'Test notification',
                'priority' => 'high',
                'recipients' => [
                    'test@gmail.com',
                ],
                'idempotency_key' => uniqid(),
            ]
        );

        $response->assertStatus(201);

        $this->assertDatabaseHas(
            'notifications',
            [
                'recipient' => 'test@gmail.com',
            ]
        );
    }
}
