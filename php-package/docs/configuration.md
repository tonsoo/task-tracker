# Configuração

O pacote lê de `config/task-tracker.php`. Publique o arquivo e defina as variáveis de ambiente abaixo.

```env
# WhatsApp
WHATSAPP_TOKEN=seu_token_whatsapp
WHATSAPP_FROM_NUMBER=15551234567
WHATSAPP_FROM_ID=seu_phone_number_id
WHATSAPP_SECRET=seu_verify_token

# Trello
TASK_TRACKER_MANAGER=trello
TRELLO_KEY=sua_chave_trello
TRELLO_TOKEN=seu_token_trello
TRELLO_BOARD_ID=id_do_board
TRELLO_LIST_ID=id_da_lista

# OpenAI
OPENAI_API_KEY=sk-...
OPENAI_MODEL=gpt-4.1-mini

# Transcricoes
TRANSCRIBER_SECRET_KEY=seu_token
```

- `messaging.whatsapp.*`: usado por `WhatsappService` e pela verificação no `MessagingController`
- `task_manager`: define o gerenciador ativo (ex: `trello`)
- `task_managers.*.driver`: classe que implementa `TaskManager`
- `task_managers.trello.*`: usado pelo `TrelloTaskManager` e `TrelloService` para operações no board/lista
- `ai.openai.*`: usado por `OpenAILLMClient` e pelos limiares consumidos no `TaskOrchestrator`
- `transcriptions.secret_key`: token Bearer para o endpoint de transcricoes
