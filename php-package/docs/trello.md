# Integração com o Trello

- Serviço: `Tonso\TaskTracker\Services\Trello\TrelloService`
- Task manager: `Tonso\TaskTracker\Integrations\Trello\TrelloTaskManager`
- Objetos de valor: `src/Objects/Trello/{Board, BoardList, Card, Action}`

## Operações
- Busca de boards, listas e cards
- Helpers de card: criar, renomear, mover, atualizar descrição, arquivar
- Comentários (usado para registrar mensagens do WhatsApp / resoluções)
- Labels e custom fields (campos personalizados)

## Regras de orquestração
- `bug_report`: atualiza card similar existente (comenta) ou cria um novo
- `bug_fixed`: comenta com a resolução e arquiva
- `feature_request`: cria um novo card

## Configuração
- Defina `TASK_TRACKER_DRIVER=trello`
- Defina `TRELLO_KEY`, `TRELLO_TOKEN`, `TRELLO_BOARD_ID`, `TRELLO_LIST_ID`
