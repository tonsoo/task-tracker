# Usage

This section explains the runtime flow and how to interact with webhook endpoints.

## Webhook Endpoints
- `GET /webhooks/messaging/{driver}`: verification (driver-specific)
- `POST /webhooks/messaging/{driver}`: message ingestion

## Processing Flow
1. `MessagingWebhookController@ingest` receives the payload
2. The configured driver converts it to `IncomingMessage` items
3. Each message is processed by `ProcessIncomingMessage`
4. `AiIntentAnalyzer` extracts one or more `StructuredIntent` objects
5. `TaskOrchestrator` creates, updates, or closes tasks

## Queue
- Run a worker: `php artisan queue:work`
- Each message is processed in a job (`ProcessIncomingMessageJob`)
- The job uses a short lock (when a message ID exists) to avoid duplicates

## Sending Responses (Optional)
Use the WhatsApp service if you need to send responses:
- `Tonso\\TaskTracker\\Services\\WhatsappService::sendMessage($message, $to)`

## Related Docs
- [HTTP & Webhooks](../integrations/http-webhooks.md)
- [AI Overview](../ai/overview.md)
- [Task Managers](../drivers/task-managers.md)
