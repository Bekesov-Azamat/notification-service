<?php

namespace App\Services\Redis;

use Illuminate\Support\Facades\Redis;

class IdempotencyService
{
    protected int $ttl = 3600;

    public function exists(string $key): bool
    {
        return Redis::exists(
            $this->buildKey($key)
        );
    }

    public function store(string $key): void
    {
        Redis::setex(
            $this->buildKey($key),
            $this->ttl,
            true
        );
    }

    protected function buildKey(string $key): string
    {
        return 'idempotency:' . $key;
    }
}
