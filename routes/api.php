<?php

use Illuminate\Support\Facades\Route;
use Tonsoo\TaskTracker\Http\Controllers\MessagingWebhookController;
use Tonsoo\TaskTracker\Http\Controllers\TranscriptController;
use Tonsoo\TaskTracker\Http\Middleware\ValidateBearerToken;

Route::group(['prefix' => 'webhooks'], function () {

    Route::group(['prefix' => 'messaging'], function () {
        $drivers = array_keys(config('task-tracker.messaging.drivers', []));

        foreach ($drivers as $driver) {
            Route::get($driver, [MessagingWebhookController::class, 'auth'])
                ->defaults('driver', $driver)
                ->name("messaging.{$driver}.auth");

            Route::post($driver, [MessagingWebhookController::class, 'ingest'])
                ->defaults('driver', $driver)
                ->name("messaging.{$driver}.ingest");
        }
    });

    Route::options('transcribe/{meetingId}', function () {
        return response('', 200)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'POST, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    });
    Route::post('transcribe/{meetingId}', [TranscriptController::class, 'transcribe'])->name('transcribe')->middleware(ValidateBearerToken::class);
});
