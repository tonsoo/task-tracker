<?php

namespace Tonsoo\TaskTracker\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Tonsoo\TaskTracker\Models\Transcript;
use Tonsoo\TaskTracker\UseCases\ProcessTranscript;

class ProcessCompleteTranscript implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Transcript $transcript) {}

    public function handle(ProcessTranscript $useCase): void
    {
        try {
            Log::info("Processing finalized transcript for: " . $this->transcript->meeting_id);

            $useCase->handle($this->transcript);

            $this->transcript->update(['status' => 'completed']);
        } catch (Exception $e) {
            $this->transcript->update(['status' => 'active']);
            throw $e;
        }
    }
}