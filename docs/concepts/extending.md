# Extending the Package

This page covers the main extension points: AI drivers, messaging drivers, orchestration, and task backends.

## Replace the LLM Client
Implement `AiDriver` and register it in config:

```php
use Tonso\TaskTracker\Contracts\AiDriver;
use Tonso\TaskTracker\AI\Contracts\LLMClient;

class MyAiDriver implements AiDriver
{
    public function makeClient(array $config): LLMClient
    {
        return new MyLLMClient(...);
    }
}

// config/task-tracker.php
'ai' => [
    'driver' => 'custom',
    'drivers' => [
        'custom' => [
            'driver' => MyAiDriver::class,
        ],
    ],
],
```

## Add a New Messaging Driver
Implement `MessagingDriver` and map the payload to `IncomingMessage[]`. Register it under `messaging.drivers`.

## Customize Orchestration
Decorate or replace `TaskOrchestrator` if you need different routing logic for `bug_report`, `feature_request`, or `bug_fixed`.

## Create a New Task Integration
Implement `TaskDriver` and return a `TaskManager`:

```php
use Tonso\TaskTracker\Contracts\TaskDriver;
use Tonso\TaskTracker\Contracts\TaskManager;

class MyTaskDriver implements TaskDriver
{
    public function makeManager(array $config): TaskManager
    {
        return new MyTaskManager(/* ... */);
    }
}
```

Register it in `config/task-tracker.php`:

```php
'task_driver' => 'custom',
'task_drivers' => [
    'custom' => [
        'driver' => MyTaskDriver::class,
    ],
],
```

## Related Docs
- [Task Managers](../drivers/task-managers.md)
- [Messaging Adapters](../integrations/adapters.md)
- [AI Overview](../ai/overview.md)
