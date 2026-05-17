<?php

namespace Tests\Feature\Notification;

use App\Jobs\SendNotificationJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class NotificationQueueTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_job_is_dispatched(): void
    {
        Bus::fake();

        $this->postJson(
            '/api/v1/notifications',
            [
                'channel' => 'email',
                'message' => 'Queue test',
                'priority' => 'high',
                'recipients' => [
                    'queue@gmail.com',
                ],
                'idempotency_key' => uniqid(),
            ]
        );

        Bus::assertDispatched(
            SendNotificationJob::class
        );
    }
}
