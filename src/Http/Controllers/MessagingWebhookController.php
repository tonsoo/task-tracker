<?php

namespace Tonsoo\TaskTracker\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Tonsoo\TaskTracker\Messaging\MessagingDriverRegistry;
use Tonsoo\TaskTracker\Models\IncomingMessage;

class MessagingWebhookController extends Controller
{
    public function auth(Request $request, string $driver, MessagingDriverRegistry $registry)
    {
        return $registry->get($driver)->verify($request);
    }

    public function ingest(Request $request, string $driver, MessagingDriverRegistry $registry)
    {
        Log::info("Received messaging request for driver: {$driver}");

        $messages = $registry->get($driver)->parse($request);

        IncomingMessage::insert($messages);

        return response()->json(['ok' => true]);
    }
}
