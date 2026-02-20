<?php

namespace Tonso\TaskTracker\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Tonso\TaskTracker\Messaging\Adapters\WhatsAppAdapter;
use Tonso\TaskTracker\Models\IncomingMessage;

class MessagingController extends Controller
{
    public function whatsappAuth(Request $request)
    {
        $verifyToken = config('task-tracker.messaging.whatsapp.secret');

        if (
            $request->get('hub_mode') === 'subscribe' &&
            $request->get('hub_verify_token') === $verifyToken
        ) {
            return response(
                $request->get('hub_challenge'),
                200
            );
        }

        return response('Unauthorized', 403);
    }

    public function whatsapp(
        Request $request,
        WhatsAppAdapter $adapter
    ) {
        Log::info('Received whatsapp request');
        $messages = $adapter->parse($request->all());

        IncomingMessage::insert($messages);

        return response()->json(['ok' => true]);
    }
}