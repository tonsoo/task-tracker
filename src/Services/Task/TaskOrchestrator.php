<?php

namespace Tonso\TaskTracker\Services\Task;

use Tonso\TaskTracker\AI\AiIntentAnalyzer;
use Tonso\TaskTracker\AI\DTO\StructuredIntent;
use Tonso\TaskTracker\Contracts\TaskManager;
use Tonso\TaskTracker\Objects\Task\TaskItem;

final class TaskOrchestrator
{
    public function __construct(
        private readonly TaskManager $tasks,
        private readonly AiIntentAnalyzer $ai,
    ) {}

    public function handle(StructuredIntent $intent): void
    {
        $task = $this->findMatchingTask($intent);

        if ($task) {
            if ($intent->type === 'bug_fixed') {
                $this->tasks->closeTask($task, $intent);
                return;
            }

            $this->tasks->updateTask($task, $intent);
            return;
        }

        if ($intent->type === 'bug_fixed') {
            return;
        }

        $this->tasks->createTask($intent);
    }

    private function findMatchingTask(StructuredIntent $intent): ?TaskItem
    {
        $tasks = $this->tasks->tasks();

        foreach ($tasks as $task) {
            $taskCanonical = $this->tasks->extractCanonical($task);

            if ($taskCanonical && !empty($intent->canonical)) {
                if ($this->canonicalEquals($intent, $taskCanonical)) {
                    return $task;
                }
            }
        }

        $slimTasks = $tasks->map(fn (TaskItem $task) => $this->tasks->toSlimArray($task));

        foreach ($slimTasks->chunk(100) as $chunk) {
            $result = $this->ai->findMatchInBatch(
                newIntent: "{$intent->type} | {$intent->title} | {$intent->description}",
                candidates: $chunk->values()->toArray()
            );

            if (($result['match'] ?? false) &&
                ($result['confidence'] ?? 0) >= config('task-tracker.ai.similarity_threshold')) {
                $matchId = $result['task_id'] ?? null;
                return $tasks->first(fn (TaskItem $task) => $task->id() === $matchId);
            }
        }

        return null;
    }

    private function canonicalEquals(StructuredIntent $intent, array $taskCanonical): bool
    {
        return
            ($intent->canonical['object'] ?? null) === ($taskCanonical['object'] ?? null)
            && ($intent->canonical['action'] ?? null) === ($taskCanonical['action'] ?? null);
    }
}
