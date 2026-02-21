<?php

namespace Tonso\TaskTracker\Integrations\Trello;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Tonso\TaskTracker\AI\DTO\StructuredIntent;
use Tonso\TaskTracker\Contracts\TaskManager;
use Tonso\TaskTracker\Objects\Task\TaskItem;
use Tonso\TaskTracker\Objects\Trello\Card;
use Tonso\TaskTracker\Services\Trello\TrelloService;

final class TrelloTaskManager implements TaskManager
{
    public function __construct(
        private readonly TrelloService $trello
    ) {}

    public function tasks(): Collection
    {
        return $this->trello->cards()->map(function (Card $card) {
            return new TaskItem(
                id: $card->id(),
                title: $card->name(),
                description: $card->description(),
                raw: $card,
            );
        });
    }

    public function createTask(StructuredIntent $intent): TaskItem
    {
        $prefix = match ($intent->type) {
            'bug_report'      => '🐞 ',
            'feature_request' => '✨ ',
            default           => '📝 ',
        };

        $canonicalBlock = '';
        if (!empty($intent->canonical)) {
            $canonicalBlock = "\n\n<!-- canonical:" . json_encode($intent->canonical) . " -->";
        }

        $card = $this->trello->createCard(
            name: $prefix.$intent->title,
            desc: ($intent->description ?? '') . $canonicalBlock
        );

        return new TaskItem(
            id: $card->id(),
            title: $card->name(),
            description: $card->description(),
            raw: $card,
        );
    }

    public function updateTask(TaskItem $task, StructuredIntent $intent): void
    {
        match ($intent->type) {
            'bug_report'      => $this->trello->addComment($task->id(), "🐞 New bug report:\n{$intent->description}"),
            'feature_request' => $this->trello->addComment($task->id(), "✨ Additional context:\n{$intent->description}"),
            'bug_fixed'       => $this->closeTask($task, $intent),
            default           => null,
        };
    }

    public function closeTask(TaskItem $task, StructuredIntent $intent): void
    {
        $this->trello->addComment(
            $task->id(),
            "✅ Fixed:\n{$intent->resolution}"
        );

        $this->trello->archiveCard($task->id());
    }

    public function extractCanonical(TaskItem $task): ?array
    {
        $desc = $task->description() ?? '';

        if (!preg_match('/<!-- canonical:(.*?) -->/s', $desc, $m)) {
            return null;
        }

        return json_decode(trim($m[1]), true);
    }

    public function toSlimArray(TaskItem $task): array
    {
        return [
            'id' => $task->id(),
            'title' => $task->title(),
            'summary' => Str::limit($task->description() ?? '', 500),
        ];
    }
}
