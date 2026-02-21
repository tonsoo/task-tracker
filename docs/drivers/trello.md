# Trello Integration

The Trello integration provides a `TaskManager` backed by Trello boards, lists, and cards.

## Key Classes
- Service: `Tonso\TaskTracker\Services\Trello\TrelloService`
- Task manager: `Tonso\TaskTracker\Integrations\Trello\TrelloTaskManager`
- Objects: `src/Objects/Trello/{Board, BoardList, Card, Action}`

## Supported Operations
- Fetch boards, lists, and cards
- Card helpers: create, rename, move, update description, archive
- Comments (used to log messages or resolutions)
- Labels and custom fields

## Orchestration Rules
- `bug_report`: update an existing matching card (comment) or create a new one
- `bug_fixed`: comment with resolution and archive
- `feature_request`: create a new card

## Configuration
Set these environment variables (see [Configuration](../concepts/configuration.md)):
- `TASK_TRACKER_DRIVER=trello`
- `TRELLO_KEY`
- `TRELLO_TOKEN`
- `TRELLO_BOARD_ID`
- `TRELLO_LIST_ID`

## Related Docs
- [Task Managers](task-managers.md)
- [AI Overview](../ai/overview.md)
