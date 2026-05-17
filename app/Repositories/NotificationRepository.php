<?php

namespace App\Repositories;

use App\Enums\NotificationStatus;
use App\Models\Notification;
use App\Models\NotificationBatch;
use Illuminate\Database\Eloquent\Collection;

class NotificationRepository
{
    public function createBatch(array $data): NotificationBatch
    {
        return NotificationBatch::create($data);
    }

    public function createNotification(array $data): Notification
    {
        return Notification::create($data);
    }

    public function findNotificationById(int $id): ?Notification
    {
        return Notification::with('batch')->find($id);
    }

    public function findNotificationsByRecipient(string $recipient): Collection
    {
        return Notification::where('recipient', $recipient)
            ->latest()
            ->get();
    }

    public function updateStatus(
        Notification $notification,
        NotificationStatus $status,
        ?string $error = null
    ): Notification {
        $data = [
            'status' => $status->value,
            'last_error' => $error,
        ];

        if ($status === NotificationStatus::SENT) {
            $data['sent_at'] = now();
        }

        if ($status === NotificationStatus::DELIVERED) {
            $data['delivered_at'] = now();
        }

        if ($status === NotificationStatus::FAILED) {
            $data['failed_at'] = now();
        }

        $notification->update($data);

        return $notification->fresh();
    }

    public function incrementAttempts(Notification $notification): Notification
    {
        $notification->increment('attempts_count');

        return $notification->fresh();
    }
}
