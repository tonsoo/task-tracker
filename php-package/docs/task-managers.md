# Gerenciadores de Tarefas

O Task Tracker suporta multiplas integracoes via o contrato `TaskManager`. O Trello e fornecido por padrao.

## Selecionar o gerenciador
No `config/task-tracker.php`:

```php
'task_manager' => env('TASK_TRACKER_MANAGER', 'trello'),
```

## Registrar um gerenciador customizado
Crie uma classe que implemente `TaskManager` e registre no config:

```php
'task_managers' => [
    'custom' => [
        'driver' => App\\Integrations\\MyTaskManager::class,
    ],
],
```

## Contrato esperado
Um `TaskManager` deve:
- Listar tarefas existentes (`tasks`)
- Criar tarefas (`createTask`)
- Atualizar tarefas existentes com contexto (`updateTask`)
- Fechar tarefas (`closeTask`)
- Informar identidade canonica (`extractCanonical`)
- Fornecer dados simples para deduplicacao via IA (`toSlimArray`)

O `toSlimArray` deve retornar ao menos:
`id`, `title`, `summary`.

## Implementacao Trello (padrao)
- Classe: `Tonso\TaskTracker\Integrations\Trello\TrelloTaskManager`
- Configuracao: `task_managers.trello.*`
