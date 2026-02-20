# Estendendo o Pacote

## Substituir o cliente LLM
Faça o bind da sua implementação de `LLMClient`.

```php
use Tonso\TaskTracker\AI\Contracts\LLMClient;

$this->app->singleton(LLMClient::class, fn () => new MyLLMClient(...));
```

## Adicionar um novo adapter de mensageria
Implemente `MessagingAdapter` e converta o payload da sua plataforma em DTOs `IncomingMessage`. Faça o bind onde você processa o webhook.

## Customizar a orquestração
Estenda ou decore `TaskOrchestrator` para alterar as regras de roteamento de `bug_report`, `feature_request` ou `bug_fixed`.

## Criar uma nova integracao de tarefas
Implemente o contrato `TaskManager` e registre a classe no config.

```php
use Tonso\TaskTracker\Contracts\TaskManager;
use Tonso\TaskTracker\AI\DTO\StructuredIntent;
use Tonso\TaskTracker\Objects\Task\TaskItem;
use Illuminate\Support\Collection;

class MyTaskManager implements TaskManager
{
    public function tasks(): Collection { /* ... */ }
    public function createTask(StructuredIntent $intent): TaskItem { /* ... */ }
    public function updateTask(TaskItem $task, StructuredIntent $intent): void { /* ... */ }
    public function closeTask(TaskItem $task, StructuredIntent $intent): void { /* ... */ }
    public function extractCanonical(TaskItem $task): ?array { /* ... */ }
    public function toSlimArray(TaskItem $task): array { /* ... */ }
}
```

No arquivo `config/task-tracker.php`:

```php
'task_manager' => 'custom',
'task_managers' => [
    'custom' => [
        'driver' => MyTaskManager::class,
    ],
],
```
