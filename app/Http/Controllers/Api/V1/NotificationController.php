<?php

namespace App\Http\Controllers\Api\V1;

use App\DTO\Notification\SendNotificationDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Notification\SendNotificationRequest;
use App\Repositories\NotificationRepository;
use App\Services\Notification\NotificationService;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService,
        protected NotificationRepository $notificationRepository,
    ) {}

    public function send(
        SendNotificationRequest $request
    ): JsonResponse {

        $dto = SendNotificationDTO::fromArray(
            $request->validated()
        );

        $batch = $this->notificationService->send($dto);

        return response()->json([
            'success' => true,
            'message' => 'Notifications queued successfully',
            'batch_id' => $batch->id,
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $notification = $this->notificationRepository
            ->findNotificationById($id);

        if (!$notification) {
            return response()->json([
                'message' => 'Notification not found',
            ], 404);
        }

        return response()->json($notification);
    }

    public function recipientNotifications(
        string $recipient
    ): JsonResponse {

        $notifications = $this->notificationRepository
            ->findNotificationsByRecipient($recipient);

        return response()->json($notifications);
    }
}
