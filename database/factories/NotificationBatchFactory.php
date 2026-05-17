<?php

namespace Database\Factories;

use App\Enums\NotificationChannel;
use App\Enums\NotificationPriority;
use App\Models\NotificationBatch;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationBatchFactory extends Factory
{
    protected $model = NotificationBatch::class;

    public function definition(): array
    {
        return [
            'channel' => NotificationChannel::EMAIL,
            'message' => fake()->sentence(),
            'priority' => NotificationPriority::HIGH,
            'idempotency_key' => uniqid(),
            'total_count' => 1,
        ];
    }
}
