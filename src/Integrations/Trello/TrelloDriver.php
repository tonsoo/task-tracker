<?php

namespace Tonsoo\TaskTracker\Integrations\Trello;

use Stevenmaguire\Services\Trello\Client;
use Tonsoo\TaskTracker\AI\AiIntentAnalyzer;
use Tonsoo\TaskTracker\Contracts\TaskDriver;
use Tonsoo\TaskTracker\Contracts\TaskManager;
use Tonsoo\TaskTracker\Services\Task\TaskOrchestrator;
use Tonsoo\TaskTracker\Services\Task\TrelloTaskOrchestrator;
use Tonsoo\TaskTracker\Services\Trello\TrelloService;

final class TrelloDriver implements TaskDriver
{
    public function makeManager(array $config): TaskManager
    {
        $client = new Client([
            'key'   => $config['key'] ?? null,
            'token' => $config['token'] ?? null,
        ]);

        $service = new TrelloService(
            client: $client,
            boardId: $config['board_id'] ?? '',
            defaultListId: $config['default_list_id'] ?? '',
        );

        return new TrelloTaskManager($service);
    }

    public function makeOrchestrator(array $config, AiIntentAnalyzer $ai): TaskOrchestrator
    {
        return new TrelloTaskOrchestrator(
            tasks: $this->makeManager($config),
            ai: $ai,
        );
    }
}
