# AI Overview

The AI layer turns raw messages into structured intents used by the task orchestrator.

## Key Classes
- Analyzer: `Tonsoo\TaskTracker\AI\AiIntentAnalyzer`
- LLM client contract: `Tonsoo\TaskTracker\AI\Contracts\LLMClient`
- Default client: `Tonsoo\TaskTracker\AI\Clients\OpenAILLMClient`

## Single vs Flexible Extraction
- `AiIntentAnalyzer::analyze($message)`
  - Always returns exactly one `StructuredIntent`.
- `AiIntentAnalyzer::analyzeFlexible($message)`
  - Lets the model decide if the input contains **zero, one, or many tasks**.
  - Returns an array of `StructuredIntent`.

Use `analyzeFlexible()` for real-world messages that may include multiple issues or none at all.

## Batch Extraction (with Context)
`AiIntentAnalyzer::analyzeBatch($context)`
- Used for processing many messages together
- The prompt understands `[ALREADY SAVED]` and `[NEW]` markers
- Returns an array of tasks in a `{ "tasks": [...] }` JSON payload

## De-duplication
`AiIntentAnalyzer::findMatchInBatch($newIntent, $candidates)` returns:
```json
{ "match": true, "task_id": "...", "confidence": 0.91, "reason": "..." }
```
This is used by `TaskOrchestrator` to decide whether to update an existing task or create a new one.

## Related Docs
- [OpenAI Driver](openai.md)
- [Usage](../concepts/usage.md)
