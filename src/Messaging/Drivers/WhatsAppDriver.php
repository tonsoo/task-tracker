<?php

namespace Tonso\TaskTracker\Messaging\Drivers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tonso\TaskTracker\Messaging\Adapters\WhatsAppAdapter;
use Tonso\TaskTracker\Messaging\Contracts\MessagingDriver;

final class WhatsAppDriver implements MessagingDriver
{
    public function __construct(
        private readonly WhatsAppAdapter $adapter,
        private readonly array $config = [],
    ) {}

    public function verify(Request $request): Response
    {
        $verifyToken = $this->config['secret'] ?? null;

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

    public function parse(Request $request): array
    {
        return $this->adapter->parse($request->all());
    }
}
