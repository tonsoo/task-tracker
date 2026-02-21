# WhatsApp Cloud API Setup

This guide helps you configure WhatsApp Cloud API to receive messages and integrate them into the Task Tracker flow.

## Prerequisites
- A Meta app with WhatsApp product enabled
- A public HTTPS endpoint for webhook callbacks

## Step-by-Step
1. Create a Meta app and enable the WhatsApp product
2. Retrieve credentials
   - `phone_number_id`
   - Access token (temporary or permanent)
3. Configure the webhook URLs
   - `POST /webhooks/messaging/whatsapp`
   - `GET /webhooks/messaging/whatsapp` (verification)
4. Set the verify token
   - Make sure `WHATSAPP_SECRET` matches the verify token in Meta
5. Subscribe to the `messages` field for your `phone_number_id`

## Environment Variables
See [Configuration](../concepts/configuration.md) and add:
```env
WHATSAPP_TOKEN=your_whatsapp_token
WHATSAPP_FROM_NUMBER=15551234567
WHATSAPP_FROM_ID=your_phone_number_id
WHATSAPP_SECRET=your_verify_token
```

## Receive Flow
- Meta sends payloads to `POST /webhooks/messaging/whatsapp`
- `WhatsAppDriver` uses `WhatsAppAdapter` to produce `IncomingMessage[]` (text only)
- Each message is queued and processed by `ProcessIncomingMessageJob`

## Debugging
- Check logs in `MessagingWebhookController`
- Test with a sample payload (replace host):
```bash
curl -X POST "https://your-host.com/webhooks/messaging/whatsapp" \
  -H "Content-Type: application/json" \
  -d '{
    "entry": [
      {
        "changes": [
          {
            "value": {
              "messages": [
                {
                  "from": "5511999999999",
                  "id": "wamid.HBgM...",
                  "type": "text",
                  "text": { "body": "App crashes when opening report" }
                }
              ]
            }
          }
        ]
      }
    ]
  }'
```

## Related Docs
- [HTTP & Webhooks](http-webhooks.md)
- [Messaging Adapters](adapters.md)
