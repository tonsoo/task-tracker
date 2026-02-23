<?php

namespace Tonsoo\TaskTracker\Contracts;

use Tonsoo\TaskTracker\AI\AiIntentAnalyzer;
use Tonsoo\TaskTracker\Services\Task\TaskOrchestrator;

interface TaskDriver
{
    public function makeManager(array $config): TaskManager;

    public function makeOrchestrator(array $config, AiIntentAnalyzer $ai): TaskOrchestrator;
}
