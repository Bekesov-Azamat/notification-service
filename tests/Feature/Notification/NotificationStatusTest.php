<?php

namespace Tests\Feature\Notification;

use App\Enums\NotificationStatus;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_has_queued_status(): void
    {
        $notification = Notification::factory()->create([
            'status' => NotificationStatus::QUEUED,
        ]);

        $this->assertEquals(
            NotificationStatus::QUEUED,
            $notification->status
        );
    }
}
