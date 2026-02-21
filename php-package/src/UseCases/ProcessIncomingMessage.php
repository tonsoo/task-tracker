<?php

namespace Tonso\TaskTracker\UseCases;

use Tonso\TaskTracker\AI\AiIntentAnalyzer;
use Tonso\TaskTracker\Services\Task\TaskOrchestrator;

final class ProcessIncomingMessage
{
    public function __construct(
        private readonly AiIntentAnalyzer $ai,
        private readonly TaskOrchestrator $orchestrator,
    ) {}

    public function handle(string $message): void
    {
        $intents = $this->ai->analyzeFlexible($message);

        foreach ($intents as $intent) {
            $this->orchestrator->handle($intent);
        }
    }
}
