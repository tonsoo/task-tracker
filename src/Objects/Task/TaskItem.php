<?php

namespace Tonso\TaskTracker\Objects\Task;

final class TaskItem
{
    public function __construct(
        private readonly string $id,
        private readonly string $title,
        private readonly ?string $description = null,
        private readonly array $meta = [],
        private readonly mixed $raw = null,
    ) {}

    public function id(): string
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function meta(): array
    {
        return $this->meta;
    }

    public function raw(): mixed
    {
        return $this->raw;
    }
}
