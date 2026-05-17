<?php

namespace App\Models;

use App\Enums\NotificationChannel;
use App\Enums\NotificationPriority;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationBatch extends Model
{
    use HasFactory;
    protected $fillable = [
        'channel',
        'message',
        'priority',
        'idempotency_key',
        'total_count',
    ];

    protected $casts = [
        'channel' => NotificationChannel::class,
        'priority' => NotificationPriority::class,
    ];

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }
}
