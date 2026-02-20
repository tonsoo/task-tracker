# Estendendo o Pacote

## Substituir o cliente LLM
Implemente um `AiDriver` e registre no config.

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

## Adicionar um novo driver de mensageria
Implemente `MessagingDriver` e converta o payload da sua plataforma em DTOs `IncomingMessage`. Registre o driver no config em `messaging.drivers`.

## Customizar a orquestração
Estenda ou decore `TaskOrchestrator` para alterar as regras de roteamento de `bug_report`, `feature_request` ou `bug_fixed`.

## Criar uma nova integracao de tarefas
Implemente o contrato `TaskDriver` e retorne um `TaskManager`. Registre a classe no config.

```php
use Tonso\TaskTracker\Contracts\TaskDriver;
use Tonso\TaskTracker\Contracts\TaskManager;
use Tonso\TaskTracker\AI\DTO\StructuredIntent;
use Tonso\TaskTracker\Objects\Task\TaskItem;
use Illuminate\Support\Collection;

class MyTaskDriver implements TaskDriver
{
    public function makeManager(array $config): TaskManager
    {
        return new MyTaskManager(/* ... */);
    }
}
```

No arquivo `config/task-tracker.php`:

```php
'task_driver' => 'custom',
'task_drivers' => [
    'custom' => [
        'driver' => MyTaskDriver::class,
    ],
],
```
