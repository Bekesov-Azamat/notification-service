<?php

namespace App\Services\Notification;

use App\Enums\NotificationStatus;
use App\Models\Notification;
use App\Repositories\NotificationRepository;

class NotificationStatusService
{
    public function __construct(
        protected NotificationRepository $notificationRepository,
    ) {}

    public function markAsSent(Notification $notification): Notification
    {
        return $this->notificationRepository->updateStatus(
            notification: $notification,
            status: NotificationStatus::SENT,
        );
    }

    public function markAsDelivered(Notification $notification): Notification
    {
        return $this->notificationRepository->updateStatus(
            notification: $notification,
            status: NotificationStatus::DELIVERED,
        );
    }

    public function markAsFailed(
        Notification $notification,
        string $error
    ): Notification {
        return $this->notificationRepository->updateStatus(
            notification: $notification,
            status: NotificationStatus::FAILED,
            error: $error,
        );
    }
}
