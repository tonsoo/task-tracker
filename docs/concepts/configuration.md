# Configuration

The package reads from `config/task-tracker.php`. Publish it and define environment variables below.

## Environment Variables
```env
# WhatsApp
WHATSAPP_TOKEN=your_whatsapp_token
WHATSAPP_FROM_NUMBER=15551234567
WHATSAPP_FROM_ID=your_phone_number_id
WHATSAPP_SECRET=your_verify_token

# Task driver
TASK_TRACKER_DRIVER=trello
TRELLO_KEY=your_trello_key
TRELLO_TOKEN=your_trello_token
TRELLO_BOARD_ID=your_board_id
TRELLO_LIST_ID=your_list_id

# AI
TASK_TRACKER_AI_DRIVER=openai
OPENAI_API_KEY=sk-...
OPENAI_MODEL=gpt-4.1-mini

# Transcriptions
TRANSCRIBER_SECRET_KEY=your_token
```

## Config Map
- `messaging.drivers.*`: messaging drivers used by webhooks
- `messaging.drivers.whatsapp.*`: WhatsApp driver settings and secrets
- `task_driver`: active task driver key (for example `trello`)
- `task_drivers.*.driver`: class that implements `TaskDriver`
- `task_drivers.trello.*`: Trello credentials and board/list IDs
- `ai.driver`: active AI driver key
- `ai.drivers.*.driver`: class that implements `AiDriver`
- `ai.drivers.openai.*`: OpenAI credentials and model
- `ai.similarity_threshold`: de-duplication confidence threshold
- `transcriptions.secret_key`: bearer token for transcript webhook

## Related Docs
- [Usage](usage.md)
- [AI Overview](../ai/overview.md)
- [Task Managers](../drivers/task-managers.md)
