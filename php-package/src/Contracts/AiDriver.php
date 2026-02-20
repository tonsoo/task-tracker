<?php

namespace Tonso\TaskTracker\Contracts;

use Tonso\TaskTracker\AI\Contracts\LLMClient;

interface AiDriver
{
    public function makeClient(array $config): LLMClient;
}
