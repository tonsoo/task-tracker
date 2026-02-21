<?php

namespace Tonsoo\TaskTracker\AI\Drivers;

use Tonsoo\TaskTracker\AI\Clients\OpenAILLMClient;
use Tonsoo\TaskTracker\AI\Contracts\LLMClient;
use Tonsoo\TaskTracker\Contracts\AiDriver;

final class OpenAIDriver implements AiDriver
{
    public function makeClient(array $config): LLMClient
    {
        return new OpenAILLMClient(
            apiKey: $config['key'] ?? null,
            model: $config['model'] ?? 'gpt-4.1-mini',
        );
    }
}
