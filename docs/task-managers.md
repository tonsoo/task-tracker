# Task Managers

Task Tracker supports multiple task backends through the `TaskManager` contract. Trello is the default implementation.

## Select the Driver
In `config/task-tracker.php`:
```php
'task_driver' => env('TASK_TRACKER_DRIVER', 'trello'),
```

## Register a Custom Driver
Create a class that implements `TaskDriver` and returns a `TaskManager`:

```php
'task_drivers' => [
    'custom' => [
        'driver' => App\Integrations\MyTaskDriver::class,
    ],
],
```

## Required Contract Behavior
A `TaskManager` must:
- List existing tasks (`tasks`)
- Create tasks (`createTask`)
- Update tasks with context (`updateTask`)
- Close tasks (`closeTask`)
- Return canonical identity (`extractCanonical`)
- Provide slim data for AI de-duplication (`toSlimArray`)

`toSlimArray` must include at least: `id`, `title`, `summary`.

## Trello Implementation (Default)
- Driver: `Tonso\TaskTracker\Integrations\Trello\TrelloDriver`
- Manager: `Tonso\TaskTracker\Integrations\Trello\TrelloTaskManager`
- Config: `task_drivers.trello.*`

## Using Alternate Credentials (Per Call)
Use `TaskManagerFactory` when you need different credentials for a specific use case without changing global config:

```php
use Tonso\TaskTracker\Services\Task\TaskManagerFactory;

$manager = app(TaskManagerFactory::class)->make([
    'key' => 'ALT_TRELLO_KEY',
    'token' => 'ALT_TRELLO_TOKEN',
    'board_id' => 'ALT_BOARD_ID',
    'default_list_id' => 'ALT_LIST_ID',
]);
```

## Related Docs
- [Trello Integration](trello.md)
- [Extending the Package](extending.md)
