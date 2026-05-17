<?php

namespace App\Services\Notification;

use App\DTO\Notification\SendNotificationDTO;
use App\Enums\NotificationStatus;
use App\Models\NotificationBatch;
use App\Repositories\NotificationRepository;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    public function __construct(
        protected NotificationRepository $notificationRepository,
    ) {}

    public function send(SendNotificationDTO $dto): NotificationBatch
    {
        return DB::transaction(function () use ($dto) {
            $batch = $this->notificationRepository->createBatch([
                'channel' => $dto->channel,
                'message' => $dto->message,
                'priority' => $dto->priority,
                'idempotency_key' => $dto->idempotencyKey,
                'total_count' => count($dto->recipients),
            ]);

            foreach ($dto->recipients as $recipient) {
                $this->notificationRepository->createNotification([
                    'notification_batch_id' => $batch->id,
                    'channel' => $dto->channel,
                    'recipient' => $recipient,
                    'message' => $dto->message,
                    'priority' => $dto->priority,
                    'status' => NotificationStatus::QUEUED->value,
                ]);
            }

            return $batch;
        });
    }
}
