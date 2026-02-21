<?php

namespace Tonsoo\TaskTracker\Contracts;

use Tonsoo\TaskTracker\AI\Contracts\LLMClient;

interface AiDriver
{
    public function makeClient(array $config): LLMClient;
}
