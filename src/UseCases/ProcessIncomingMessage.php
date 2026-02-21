<?php

namespace Tonsoo\TaskTracker\UseCases;

use Tonsoo\TaskTracker\AI\AiIntentAnalyzer;
use Tonsoo\TaskTracker\Services\Task\TaskOrchestrator;

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
