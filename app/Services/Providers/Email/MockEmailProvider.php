<?php

namespace App\Services\Providers\Email;

use App\Services\Providers\Contracts\NotificationProviderInterface;
use Exception;

class MockEmailProvider implements NotificationProviderInterface
{
    public function send(
        string $recipient,
        string $message
    ): bool {

        sleep(1);

        if (random_int(1, 10) <= 2) {
            throw new Exception('Mock email provider temporary error');
        }

        return true;
    }
}
