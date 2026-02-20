<?php

namespace Tonso\TaskTracker\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Tonso\TaskTracker\Models\Transcript;

class TranscriptController extends Controller
{
    public function transcribe(Request $request, string $meetingId): \Illuminate\Http\JsonResponse
    {
        $data = $request->validate([
            'endedAt' => 'required',
            'transcript' => 'required|array'
        ]);

        $record = Transcript::firstOrNew(['meeting_id' => $meetingId]);

        $existingMessages = collect($record->body ?? [])
            ->map(fn ($item) => $this->normalizeTranscriptItem($item, keepProcessed: true));

        $incomingMessages = collect($data['transcript'])
            ->map(fn ($item) => $this->normalizeTranscriptItem($item, keepProcessed: false));

        $merged = $existingMessages
            ->concat($incomingMessages)
            ->unique(function ($item) {
                $shortTime = substr($item['timestamp'], 0, 19);
                return $shortTime . $item['text'];
            })
            ->sortBy('timestamp')
            ->values()
            ->toArray();

        $record->status = 'active';
        $record->body = $merged;
        $record->ended_at = $data['endedAt'] ?: null;
        $record->save();

        return response()->json([
            'status' => 'merged',
            'count' => count($merged)
        ])
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'POST, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    }

    private function normalizeTranscriptItem(array $item, bool $keepProcessed): array
    {
        return [
            'timestamp' => (string) ($item['timestamp'] ?? ''),
            'text' => (string) ($item['text'] ?? ''),
            'processed' => $keepProcessed && (bool)($item['processed'] ?? false),
        ];
    }
}
