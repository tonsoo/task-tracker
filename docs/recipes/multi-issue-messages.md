# Multi-Issue Messages

Real-world messages often include multiple problems or none at all. Use flexible analysis so the AI can decide whether to return zero, one, or many tasks.

## Example
```php
$intents = $ai->analyzeFlexible($message);

foreach ($intents as $intent) {
    $orchestrator->handle($intent);
}
```

## When to Use
- A single message may contain multiple issues
- You do not want to force a task when there is no actionable issue
- You want the model to decide how to split tasks
