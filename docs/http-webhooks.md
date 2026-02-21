# HTTP & Webhooks

All webhook routes are defined in `routes/api.php` and auto-loaded by the service provider.

## Messaging Routes
For each driver in `messaging.drivers`, the package registers:
- `GET /webhooks/messaging/{driver}` → `MessagingWebhookController@auth`
- `POST /webhooks/messaging/{driver}` → `MessagingWebhookController@ingest`

## Verification
Each driver is responsible for verification. For WhatsApp:
- Compares `hub_verify_token` with `config('task-tracker.messaging.drivers.whatsapp.secret')`
- Returns `hub_challenge` when valid

## Payload Parsing
`MessagingWebhookController@ingest` delegates to the configured driver, which uses its adapter to generate `IncomingMessage[]`. The WhatsApp driver only accepts text messages.

## Related Docs
- [Messaging Adapters](adapters.md)
- [WhatsApp Cloud API Setup](whatsapp-setup.md)
