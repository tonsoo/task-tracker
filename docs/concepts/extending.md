# Extending the Package

This page covers the main extension points: AI drivers, messaging drivers, orchestration, and task backends.

## Replace the LLM Client
Implement `AiDriver` and register it in config:

```php
use Tonsoo\TaskTracker\Contracts\AiDriver;
use Tonsoo\TaskTracker\AI\Contracts\LLMClient;

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
Implement `TaskDriver` and return both a `TaskManager` and a `TaskOrchestrator`:

```php
use Tonsoo\TaskTracker\AI\AiIntentAnalyzer;
use Tonsoo\TaskTracker\Contracts\TaskDriver;
use Tonsoo\TaskTracker\Contracts\TaskManager;
use Tonsoo\TaskTracker\Services\Task\TaskOrchestrator;
use Tonsoo\TaskTracker\Services\Task\MyTaskOrchestrator;

class MyTaskDriver implements TaskDriver
{
    public function makeManager(array $config): TaskManager
    {
        return new MyTaskManager(/* ... */);
    }

    public function makeOrchestrator(array $config, AiIntentAnalyzer $ai): TaskOrchestrator
    {
        return new MyTaskOrchestrator(
            tasks: $this->makeManager($config),
            ai: $ai,
        );
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
