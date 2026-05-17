<?php

namespace Tests\Feature\Notification;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_show_route_exists(): void
    {
        $response = $this->get(
            '/api/v1/notifications/999'
        );

        $response->assertStatus(404);
    }

    public function test_recipient_notifications_route_exists(): void
    {
        $response = $this->get(
            '/api/v1/recipients/test@gmail.com/notifications'
        );

        $response->assertStatus(200);
    }
}
