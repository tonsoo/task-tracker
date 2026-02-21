# OpenAI Driver

Task Tracker ships with an OpenAI driver that implements the `LLMClient` contract.

## Configuration
Set these environment variables (see [Configuration](../concepts/configuration.md)):
- `TASK_TRACKER_AI_DRIVER=openai`
- `OPENAI_API_KEY`
- `OPENAI_MODEL` (default: `gpt-4.1-mini`)

## Driver Classes
- Driver: `Tonsoo\TaskTracker\AI\Drivers\OpenAIDriver`
- Client: `Tonsoo\TaskTracker\AI\Clients\OpenAILLMClient`

## Behavior
- Builds prompts via `AiIntentAnalyzer`
- Expects JSON-only responses
- Throws on invalid JSON

## Related Docs
- [AI Overview](overview.md)
