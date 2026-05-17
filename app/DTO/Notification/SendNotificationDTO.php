<?php

namespace App\DTO\Notification;

class SendNotificationDTO
{
    public function __construct(
        public readonly string $channel,
        public readonly string $message,
        public readonly string $priority,
        public readonly array $recipients,
        public readonly ?string $idempotencyKey,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            channel: $data['channel'],
            message: $data['message'],
            priority: $data['priority'],
            recipients: $data['recipients'],
            idempotencyKey: $data['idempotency_key'] ?? self::makeKey($data),
        );
    }
    private static function makeKey(array $data): string
    {
        return hash('sha256', json_encode([
            'channel' => $data['channel'],
            'message' => $data['message'],
            'priority' => $data['priority'],
            'recipients' => $data['recipients'],
        ]));
    }
}
