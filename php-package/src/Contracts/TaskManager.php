<?php

namespace Tonso\TaskTracker\Contracts;

use Illuminate\Support\Collection;
use Tonso\TaskTracker\AI\DTO\StructuredIntent;
use Tonso\TaskTracker\Objects\Task\TaskItem;

interface TaskManager
{
    /**
     * @return Collection<TaskItem>
     */
    public function tasks(): Collection;

    public function createTask(StructuredIntent $intent): TaskItem;

    public function updateTask(TaskItem $task, StructuredIntent $intent): void;

    public function closeTask(TaskItem $task, StructuredIntent $intent): void;

    public function extractCanonical(TaskItem $task): ?array;

    public function toSlimArray(TaskItem $task): array;
}
