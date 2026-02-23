<?php

namespace Tonsoo\TaskTracker\Services\Task;

use Tonsoo\TaskTracker\AI\DTO\StructuredIntent;

abstract class TaskOrchestrator
{
    abstract public function handle(StructuredIntent $intent): void;
}
