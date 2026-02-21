<?php

namespace Tonsoo\TaskTracker\Services;

use GuzzleHttp\Client;

class WhatsappService
{
    private string $token;
    private string $fromId;

    public function __construct()
    {
        $this->token = config('task-tracker.messaging.drivers.whatsapp.token');
        $this->fromId = config('task-tracker.messaging.drivers.whatsapp.from.id');
    }

    public function sendMessage(string $message, string $to): void
    {
        $client = new Client([
            'headers' => [
                'Authorization' => "Bearer {$this->token}",
                'Content-Type' => 'application/json',
            ]
        ]);

        // basic whatsapp send api
        $client->post(
            "https://graph.facebook.com/v22.0/{$this->fromId}/messages",
            [
                'json' => [
                    "messaging_product" => "whatsapp",
                    "recipient_type" => "individual",
                    "to" => $to,
                    "type" => "text",
                    "text" => [
                        "preview_url" => false,
                        "body" => $message
                    ]
                ]
            ]
        );
    }
}
