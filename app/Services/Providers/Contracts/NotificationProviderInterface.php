<?php

namespace App\Services\Providers\Contracts;

interface NotificationProviderInterface
{
    public function send(
        string $recipient,
        string $message,
    ): bool;
}
