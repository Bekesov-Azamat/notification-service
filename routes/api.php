<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\NotificationController;




Route::prefix('v1')->group(function () {

    Route::post(
        '/notifications',
        [NotificationController::class, 'send']
    );

    Route::get(
        '/notifications/{id}',
        [NotificationController::class, 'show']
    );

    Route::get(
        '/recipients/{recipient}/notifications',
        [NotificationController::class, 'recipientNotifications']
    );
});
