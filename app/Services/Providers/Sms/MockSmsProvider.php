<?php

namespace App\Services\Providers\Sms;

use App\Services\Providers\Contracts\NotificationProviderInterface;
use Exception;

class MockSmsProvider implements NotificationProviderInterface
{
    public function send(
        string $recipient,
        string $message
    ): bool {

        sleep(1);

        if (random_int(1, 10) <= 3) {
            throw new Exception('Mock SMS provider temporary error');
        }

        return true;
    }
}
