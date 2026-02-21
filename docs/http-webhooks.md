# HTTP & Webhooks

## Rotas
Definidas em `routes/api.php` e carregadas automaticamente pelo Service Provider.

Para cada driver em `messaging.drivers`, o pacote registra:
`GET /webhooks/messaging/{driver}` → `MessagingWebhookController@auth`
`POST /webhooks/messaging/{driver}` → `MessagingWebhookController@ingest`

## Verificação
Cada driver e responsavel por autenticar o webhook. O driver do WhatsApp compara `hub_verify_token` com `config('task-tracker.messaging.drivers.whatsapp.secret')` e devolve `hub_challenge` quando valido.

## Interpretação do payload
`ingest()` delega ao driver configurado, que por sua vez usa o adapter e gera `IncomingMessage[]` apenas para mensagens de texto (no caso do WhatsApp).
