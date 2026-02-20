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
