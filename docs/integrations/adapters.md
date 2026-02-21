# Messaging Adapters

Messaging drivers translate platform-specific payloads into `IncomingMessage` DTOs. Some platforms require an adapter layer to normalize the payload before the driver produces `IncomingMessage` records.

## Core Interfaces
- Driver contract: `Tonso\TaskTracker\Messaging\Contracts\MessagingDriver`
- Adapter contract: `Tonso\TaskTracker\Messaging\Contracts\MessagingAdapter`
- Built-in driver: `Tonso\TaskTracker\Messaging\Drivers\WhatsAppDriver`
- Built-in adapter: `Tonso\TaskTracker\Messaging\Adapters\WhatsAppAdapter`

## WhatsApp Driver Behavior
- Iterates `entry[].changes[].value.messages[]`
- Accepts only `type === 'text'`
- Produces `IncomingMessage(platform: 'whatsapp', senderId, text, rawPayload)`

## Creating a New Driver
```php
namespace App\Messaging\Drivers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tonso\TaskTracker\Messaging\Contracts\MessagingDriver;

final class TelegramDriver implements MessagingDriver
{
    public function verify(Request $request): Response
    {
        return response('', 200);
    }

    public function parse(Request $request): array
    {
        $payload = $request->all();
        // Map Telegram payload to IncomingMessage[]
        return [
            [
                'external_id' => 'abc',
                'text' => '...',
                'source' => 'telegram',
                'processed' => false,
            ],
        ];
    }
}
```

Register the driver in `config/task-tracker.php` under `messaging.drivers.*`.

## Related Docs
- [HTTP & Webhooks](http-webhooks.md)
- [WhatsApp Cloud API Setup](whatsapp-setup.md)
