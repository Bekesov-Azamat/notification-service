<?php

namespace Tests\Feature\Notification;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_validation_works(): void
    {
        $response = $this->postJson(
            '/api/v1/notifications',
            []
        );

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'channel',
            'message',
            'priority',
            'recipients',
        ]);
    }
}
