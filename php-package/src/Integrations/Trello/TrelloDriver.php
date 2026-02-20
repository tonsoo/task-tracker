<?php

namespace Tonso\TaskTracker\Integrations\Trello;

use Stevenmaguire\Services\Trello\Client;
use Tonso\TaskTracker\Contracts\TaskDriver;
use Tonso\TaskTracker\Contracts\TaskManager;
use Tonso\TaskTracker\Services\Trello\TrelloService;

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
}
