# Adapters de Mensageria

Drivers traduzem payloads específicos da plataforma para DTOs `IncomingMessage`, usando um Adapter quando necessário.

- Contrato do driver: `Tonso\TaskTracker\Messaging\Contracts\MessagingDriver`
- Contrato do adapter: `Tonso\TaskTracker\Messaging\Contracts\MessagingAdapter`
- Driver embutido: `Tonso\TaskTracker\Messaging\Drivers\WhatsAppDriver`
- Adapter embutido: `Tonso\TaskTracker\Messaging\Adapters\WhatsAppAdapter`
- DTO: `Tonso\TaskTracker\Messaging\IncomingMessage`

## WhatsAppDriver
- Itera `entry[].changes[].value.messages[]`
- Aceita apenas `type === 'text'`
- Produz `IncomingMessage(platform: 'whatsapp', senderId, text, rawPayload)`

## Criando um novo driver

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
        // ...mapear o update do Telegram para IncomingMessage[]
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

Registre o driver em `config/task-tracker.php` em `messaging.drivers.*`.
