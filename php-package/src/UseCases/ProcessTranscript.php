<?php

namespace Tonso\TaskTracker\UseCases;

use Tonso\TaskTracker\AI\AiIntentAnalyzer;
use Tonso\TaskTracker\Models\Transcript;
use Tonso\TaskTracker\Services\Task\TaskOrchestrator;

final class ProcessTranscript
{
    public function __construct(
        private readonly AiIntentAnalyzer $ai,
        private readonly TaskOrchestrator $orchestrator,
    ) {}

    public function handle(Transcript $transcript): void
    {
        $newItems = collect($transcript->body)
            ->filter(fn ($item) => empty($item['processed']));

        if ($newItems->isEmpty()) {
            return;
        }

        $context = $newItems
            ->map(function ($item) {
                $text = $item['text'] ?? '';
                return "[NEW]: " . $text;
            })
            ->join("\n");

        $intents = $this->ai->analyzeBatch($context);

        foreach ($intents as $intent) {
            $this->orchestrator->handle($intent);
        }

        $body = collect($transcript->body)
            ->map(function ($item) {
                if (empty($item['processed'])) {
                    $item['processed'] = true;
                }
                return $item;
            })
            ->values()
            ->toArray();

        $transcript->update(['body' => $body]);
    }
}
