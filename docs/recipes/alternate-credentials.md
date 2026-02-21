# Alternate Credentials per Call

Use this when you need to run a one-off flow with different Trello credentials without changing global config.

## Example
```php
use Tonso\TaskTracker\Services\Task\TaskManagerFactory;

$manager = app(TaskManagerFactory::class)->make([
    'key' => 'ALT_TRELLO_KEY',
    'token' => 'ALT_TRELLO_TOKEN',
    'board_id' => 'ALT_BOARD_ID',
    'default_list_id' => 'ALT_LIST_ID',
]);

$manager->createTask($intent);
```

## Notes
- Only the values you pass are overridden
- Global config remains unchanged
