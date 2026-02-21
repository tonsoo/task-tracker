<?php

namespace Tonso\TaskTracker\AI\Drivers;

use Tonso\TaskTracker\AI\Clients\OpenAILLMClient;
use Tonso\TaskTracker\AI\Contracts\LLMClient;
use Tonso\TaskTracker\Contracts\AiDriver;

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
