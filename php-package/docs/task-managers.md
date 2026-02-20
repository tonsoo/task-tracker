# Gerenciadores de Tarefas

O Task Tracker suporta multiplas integracoes via o contrato `TaskManager`. O Trello e fornecido por padrao.

## Selecionar o driver
No `config/task-tracker.php`:

```php
'task_driver' => env('TASK_TRACKER_DRIVER', 'trello'),
```

## Registrar um driver customizado
Crie uma classe que implemente `TaskDriver` e retorne um `TaskManager`:

```php
'task_drivers' => [
    'custom' => [
        'driver' => App\\Integrations\\MyTaskDriver::class,
    ],
],
```

## Contrato esperado
Um `TaskDriver` deve criar um `TaskManager`. Um `TaskManager` deve:
- Listar tarefas existentes (`tasks`)
- Criar tarefas (`createTask`)
- Atualizar tarefas existentes com contexto (`updateTask`)
- Fechar tarefas (`closeTask`)
- Informar identidade canonica (`extractCanonical`)
- Fornecer dados simples para deduplicacao via IA (`toSlimArray`)

O `toSlimArray` deve retornar ao menos:
`id`, `title`, `summary`.

## Implementacao Trello (padrao)
- Driver: `Tonso\TaskTracker\Integrations\Trello\TrelloDriver`
- Manager: `Tonso\TaskTracker\Integrations\Trello\TrelloTaskManager`
- Configuracao: `task_drivers.trello.*`
