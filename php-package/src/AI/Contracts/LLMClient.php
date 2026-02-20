<?php

namespace Tonso\TaskTracker\AI\Contracts;

interface LLMClient
{
    /**
     * Must return raw JSON string
     */
    public function analyzeIntent(string $systemPrompt, string $userMessage): string;
}
