# Uso

## Endpoints de webhook
- GET `/webhooks/messaging/{driver}` → verificação (dependente do driver)
- POST `/webhooks/messaging/{driver}` → eventos de mensagem

## Fluxo de processamento
1. `MessagingWebhookController@ingest` interpreta o payload via o driver configurado
2. Despacha `ProcessIncomingMessageJob` por mensagem
3. `ProcessIncomingMessage` → `AiIntentAnalyzer` → `StructuredIntent`
4. `TaskOrchestrator` aplica regras de negócio usando o `TaskManager` configurado

## Fila
- Garanta um worker rodando: `php artisan queue:work`
- `ProcessIncomingMessageJob` usa um lock curto com base no `messageId` (quando houver) para idempotência

## Enviar respostas (opcional)
- Use `Tonso\TaskTracker\Services\WhatsappService::sendMessage($message, $to)` com seu `from.id` configurado
