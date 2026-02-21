# Webhook Testing

Use this to validate your webhook endpoints during setup.

## WhatsApp Sample Payload
Replace `your-host.com` with your public URL:
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
- [HTTP & Webhooks](../integrations/http-webhooks.md)
- [WhatsApp Cloud API Setup](../integrations/whatsapp-setup.md)
