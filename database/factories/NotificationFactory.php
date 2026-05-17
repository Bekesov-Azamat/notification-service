<?php

namespace Database\Factories;

use App\Enums\NotificationChannel;
use App\Enums\NotificationPriority;
use App\Enums\NotificationStatus;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        return [
            'notification_batch_id' => \App\Models\NotificationBatch::factory(),
            'channel' => NotificationChannel::EMAIL,
            'recipient' => fake()->safeEmail(),
            'message' => fake()->sentence(),
            'priority' => NotificationPriority::HIGH,
            'status' => NotificationStatus::QUEUED,
        ];
    }
}
