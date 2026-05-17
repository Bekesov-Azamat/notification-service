<?php

namespace App\Services\Notification;

use App\Jobs\SendNotificationJob;
use App\DTO\Notification\SendNotificationDTO;
use App\Enums\NotificationStatus;
use App\Models\NotificationBatch;
use App\Repositories\NotificationRepository;
use Illuminate\Support\Facades\DB;
use App\Services\Redis\IdempotencyService;

class NotificationService
{
    public function __construct(
        protected NotificationRepository $notificationRepository,
        protected IdempotencyService $idempotencyService,
    ) {}

    public function send(SendNotificationDTO $dto): NotificationBatch
    {
        if (
            $this->idempotencyService
            ->exists($dto->idempotencyKey)
        ) {
            abort(409, 'Duplicate request');
        }

        $this->idempotencyService
            ->store($dto->idempotencyKey);
        return DB::transaction(function () use ($dto) {
            $batch = $this->notificationRepository->createBatch([
                'channel' => $dto->channel,
                'message' => $dto->message,
                'priority' => $dto->priority,
                'idempotency_key' => $dto->idempotencyKey,
                'total_count' => count($dto->recipients),
            ]);
            foreach ($dto->recipients as $recipient) {

                $notification = $this->notificationRepository
                    ->createNotification([
                        'notification_batch_id' => $batch->id,
                        'channel' => $dto->channel,
                        'recipient' => $recipient,
                        'message' => $dto->message,
                        'priority' => $dto->priority,
                        'status' => NotificationStatus::QUEUED->value,
                    ]);

                $queueName = $dto->priority === 'high'
                    ? 'notifications_high'
                    : 'notifications_default';

                SendNotificationJob::dispatch($notification->id)
                    ->onQueue($queueName);
            }

            return $batch;
        });
    }
}
