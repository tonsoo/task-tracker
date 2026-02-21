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
- Driver: `Tonsoo\TaskTracker\Integrations\Trello\TrelloDriver`
- Manager: `Tonsoo\TaskTracker\Integrations\Trello\TrelloTaskManager`
- Config: `task_drivers.trello.*`

## Related Docs
- [Trello Integration](trello.md)
- [AI Overview](../ai/overview.md)
- [Extending the Package](../concepts/extending.md)
