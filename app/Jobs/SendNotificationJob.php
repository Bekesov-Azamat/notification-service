<?php

namespace App\Jobs;

use App\Enums\NotificationChannel;
use App\Models\Notification;
use App\Repositories\NotificationRepository;
use App\Services\Notification\NotificationStatusService;
use App\Services\Providers\Email\MockEmailProvider;
use App\Services\Providers\Sms\MockSmsProvider;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $backoff = 5;

    public function __construct(
        public int $notificationId,
    ) {}

    public function handle(
        NotificationRepository $notificationRepository,
        NotificationStatusService $statusService,
        MockEmailProvider $emailProvider,
        MockSmsProvider $smsProvider,
    ): void {

        $notification = $notificationRepository
            ->findNotificationById($this->notificationId);

        if (!$notification) {
            return;
        }

        $notificationRepository->incrementAttempts($notification);

        try {

            $provider = match ($notification->channel) {

                NotificationChannel::EMAIL =>
                $emailProvider,

                NotificationChannel::SMS =>
                $smsProvider,
            };

            $provider->send(
                recipient: $notification->recipient,
                message: $notification->message,
            );

            $statusService->markAsSent($notification);

            $statusService->markAsDelivered($notification);
        } catch (Exception $exception) {

            if ($this->attempts() >= $this->tries) {

                $statusService->markAsFailed(
                    notification: $notification,
                    error: $exception->getMessage(),
                );
            }

            throw $exception;
        }
    }
}
