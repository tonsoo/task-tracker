<?php

namespace Tonso\TaskTracker\UseCases;

use Tonso\TaskTracker\AI\AiIntentAnalyzer;
use Tonso\TaskTracker\Models\IncomingMessage;
use Tonso\TaskTracker\Services\Task\TaskOrchestrator;

final class ProcessMessageBatch
{
    public function __construct(
        private readonly AiIntentAnalyzer $ai,
        private readonly TaskOrchestrator $orchestrator,
    ) {}

    public function handle(): void
    {
        $newMessages = IncomingMessage::where('processed', false)
            ->where('created_at', '<=', now()->subSeconds(30))
            ->orderBy('created_at', 'asc')
            ->get();

        if ($newMessages->isEmpty()) return;

        $history = IncomingMessage::orderBy('created_at', 'desc')
            ->take(20)
            ->get()
            ->reverse();

        $context = $history->map(function($m) {
            $status = $m->processed ? "[ALREADY SAVED]" : "[NEW]";
            return "$status User: {$m->text}";
        })->join("\n");

        $intents = $this->ai->analyzeBatch($context);

        foreach ($intents as $intent) {
            $this->orchestrator->handle($intent);
        }

        IncomingMessage::whereIn('id', $newMessages->pluck('id'))->update(['processed' => true]);
    }
}
