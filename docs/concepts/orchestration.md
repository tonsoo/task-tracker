# Orchestration Rules

The `TaskOrchestrator` applies business rules to decide how to handle each intent.

## Default Rules
- `bug_report`: update existing matching task (comment) or create new
- `bug_fixed`: comment with resolution and archive
- `feature_request`: create a new task
- `unknown`: ignored by default

## De-duplication
The orchestrator uses:
- direct canonical matching
- AI similarity matching via `findMatchInBatch`

If no match is found, a new task is created (unless type is `bug_fixed`).

## Customizing Behavior
You can decorate or replace the orchestrator if you need custom routing logic:
- change how matches are detected
- add new intent types
- route tasks based on tags or channels

## Related Docs
- [Task Managers](../drivers/task-managers.md)
- [AI Overview](../ai/overview.md)
