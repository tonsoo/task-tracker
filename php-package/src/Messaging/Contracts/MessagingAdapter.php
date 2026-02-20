<?php

namespace Tonso\TaskTracker\Messaging\Contracts;

interface MessagingAdapter
{
    /**
     * @return array<int, array<string, mixed>> Attribute arrays for IncomingMessage model creation
     */
    public function parse(array $payload): array;
}
