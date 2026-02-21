<?php

namespace Tonso\TaskTracker\Messaging;

use Illuminate\Contracts\Container\Container;
use Tonso\TaskTracker\Messaging\Contracts\MessagingDriver;

final class MessagingDriverRegistry
{
    public function __construct(
        private readonly Container $container,
        private readonly array $drivers,
    ) {}

    public function keys(): array
    {
        return array_keys($this->drivers);
    }

    public function get(string $key): MessagingDriver
    {
        $config = $this->drivers[$key] ?? null;
        $driverClass = $config['driver'] ?? null;

        if (!$driverClass) {
            throw new \RuntimeException("Messaging driver [$key] is not configured.");
        }

        return $this->container->make($driverClass, ['config' => $config]);
    }
}
