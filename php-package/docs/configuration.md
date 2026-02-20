# Configuração

O pacote lê de `config/task-tracker.php`. Publique o arquivo e defina as variáveis de ambiente abaixo.

```env
# WhatsApp
WHATSAPP_TOKEN=seu_token_whatsapp
WHATSAPP_FROM_NUMBER=15551234567
WHATSAPP_FROM_ID=seu_phone_number_id
WHATSAPP_SECRET=seu_verify_token

# Trello
TASK_TRACKER_DRIVER=trello
TRELLO_KEY=sua_chave_trello
TRELLO_TOKEN=seu_token_trello
TRELLO_BOARD_ID=id_do_board
TRELLO_LIST_ID=id_da_lista

# OpenAI
OPENAI_API_KEY=sk-...
OPENAI_MODEL=gpt-4.1-mini
TASK_TRACKER_AI_DRIVER=openai

# Transcricoes
TRANSCRIBER_SECRET_KEY=seu_token
```

- `messaging.drivers.*`: usado pelos drivers de mensageria e pelos webhooks
- `task_driver`: define o driver ativo (ex: `trello`)
- `task_drivers.*.driver`: classe que implementa `TaskDriver`
- `task_drivers.trello.*`: usado pelo `TrelloDriver`, `TrelloTaskManager` e `TrelloService` para operações no board/lista
- `messaging.drivers.whatsapp.*`: usado pelo `WhatsAppDriver` e `WhatsappService`
- `ai.driver`: define o driver de IA ativo (ex: `openai`)
- `ai.drivers.*.driver`: classe que implementa `AiDriver`
- `ai.drivers.openai.*`: usado pelo `OpenAIDriver` para criar o cliente
- `transcriptions.secret_key`: token Bearer para o endpoint de transcricoes
