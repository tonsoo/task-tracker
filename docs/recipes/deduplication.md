# Deduplication Strategy

Task Tracker avoids duplicate tasks by combining canonical identity and AI similarity matching.

## How It Works
1. Each intent has `canonical.object` and `canonical.action`
2. If an existing task has the same canonical pair, it is considered the same task
3. If canonical matching fails, the AI similarity check runs on slim task data

## When to Tune
- You see too many duplicates
- Similar tasks are incorrectly merged

## Tuning Options
- Adjust `ai.similarity_threshold`
- Improve canonical extraction in your prompt
- Ensure `toSlimArray()` includes the right identifiers

## Related Docs
- [AI Overview](../ai/overview.md)
- [Intents](../concepts/intents.md)
