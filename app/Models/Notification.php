<?php

namespace App\Models;

use App\Enums\NotificationChannel;
use App\Enums\NotificationPriority;
use App\Enums\NotificationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'notification_batch_id',
        'channel',
        'recipient',
        'message',
        'priority',
        'status',
        'attempts_count',
        'last_error',
        'sent_at',
        'delivered_at',
        'failed_at',
    ];

    protected $casts = [
        'channel' => NotificationChannel::class,
        'priority' => NotificationPriority::class,
        'status' => NotificationStatus::class,
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(NotificationBatch::class, 'notification_batch_id');
    }
}
