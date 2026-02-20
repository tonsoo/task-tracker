<?php

namespace Tonso\TaskTracker\UseCases;

use Tonso\TaskTracker\AI\AiIntentAnalyzer;
use Tonso\TaskTracker\Services\Trello\TrelloOrchestrator;

final class ProcessIncomingMessage
{
    public function __construct(
        private readonly AiIntentAnalyzer $ai,
        private readonly TrelloOrchestrator $orchestrator,
    ) {}

    public function handle(string $message): void
    {
        $intent = $this->ai->analyze($message);

        $this->orchestrator->handle($intent);
    }
}
