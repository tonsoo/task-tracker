# Audio Transcription

The `Task Tracker` package is designed to be flexible. While the core package handles the logic of analyzing intent and managing tasks, you can easily build an entry point to handle voice commands.

This document describes the implementation details of the **Transcribe Demo** available at https://task-tracker.alysson-thoaldo.com.br, allowing you to test the speech-to-task flow using your own credentials.

---

## Live Demo Endpoint

You can test your Trello and OpenAI configurations directly against the demo endpoint:

- **URL:** `https://task-tracker.alysson-thoaldo.com.br/api/transcribe`
- **Method:** `POST`
- **Body:** `multipart/form-data`

> The `/api/transcribe` route is **not** included in the core package. It is provided here as an example of how you can utilize the package to build a voice-to-task interface.

### Demo Constraints
To ensure availability for everyone, the demo endpoint enforces the following limits:
* **Rate Limit:** 5 requests per 24 hours (per IP).
* **Max File Size:** 1 MB.
* **Max Duration:** 60 seconds.
* **Accepted Formats:** `audio/webm` or `video/webm`.

---

## Required Headers

The endpoint is driver-agnostic. You must tell it which drivers to use and provide the necessary credentials via headers. These values are used at runtime to instantiate the drivers.

| Header | Description | Example Value |
| :--- | :--- | :--- |
| `X-Task-Driver` | The task provider key | `trello` |
| `X-AI-Driver` | The LLM provider key | `openai` |
| `X-Task-Driver-Inputs` | JSON string of provider config | `{"board_id":"...","default_list_id":"...","key":"...","token":"..."}` |
| `X-AI-Driver-Inputs` | JSON string of AI config | `{"model":"gpt-4.1-mini","key":"..."}` |

---

## Using the `/api/transcribe` Demo with Tab Recorder

If you want a real-world voice-to-task workflow (Google Meet, Zoom Web, Discord in browser), the easiest and cheapest entry point is to pair the live demo endpoint with my Chrome extension:

Tab Recorder – https://github.com/tonsoo/tab-recorder

The extension captures:

- Active tab audio (meeting/call audio)
- Microphone input (your voice)
- Mixes both streams into a single `audio/webm`
- Optionally POSTs the recording automatically to a webhook as `multipart/form-data`

This allows you to test the full speech → transcription → intent detection → task creation pipeline without writing any UI code.

---

### What This Setup Enables

With Tab Recorder + the demo endpoint, the flow becomes:

1. Start recording in the extension.
2. Speak during your meeting/call.
3. Stop recording.
4. The extension uploads the WebM file to:

   https://task-tracker.alysson-thoaldo.com.br/api/transcribe

5. The endpoint:
    - Transcribes the audio
    - Analyzes intent
    - Creates tasks using your configured drivers

This is ideal for validating your Trello + OpenAI configuration end-to-end using real conversation audio.

---

## Tab Recorder → Demo Endpoint Configuration

Open the extension → Settings → Configure Webhook.

### Webhook Configuration

URL:
https://task-tracker.alysson-thoaldo.com.br/api/transcribe

Form Field (file key):
audio

Must match the expected request field in the demo route.

---

## Recording Tips for the Demo

The demo endpoint has strict limits:

- 5 requests per 24h (per IP)
- 1 MB max file size
- 60 seconds max duration
- Only `audio/webm` or `video/webm`

For best results:

- Keep recordings short.
- Speak clearly and directly.
- Stop recording shortly after speaking.
- Avoid long silence.

---

## Suggested Test Commands

During a meeting (or even alone in a test tab), record and say:

- “Create a task: Review the authentication refactor tomorrow at 10 AM.”
- “Add a Trello card: Prepare Q4 roadmap, due next Friday.”
- “Remind me to send the invoice to João after this call.”

Stop recording → The extension uploads automatically (if enabled) → The demo endpoint processes it.

---

## Production Note

The demo endpoint accepts credentials via headers for flexibility and testing purposes.

In a real production setup, you would typically:

- Store provider credentials securely per user.
- Authenticate requests (JWT, session, API key).
- Apply per-user rate limits.
- Validate ownership of task boards/projects.

The next section (“Minimal Implementation Guide”) shows how to implement your own `/transcribe` endpoint inside your Laravel application.

## Minimal Implementation Guide

If you want to implement a similar endpoint in your own Laravel application, follow this simplified guide. This example is intentionally kept basic: it is synchronous, uses standard Laravel file storage, and instantiates the drivers manually based on the request inputs.

**1. Route Definition**\
Add the POST route to your `routes/api.php` file.

```php
use App\Http\Controllers\AudioTranscriptionController;
use Illuminate\Support\Facades\Route;

Route::post('/transcribe', [AudioTranscriptionController::class, 'store']);
```

**2. The Controller Implementation**\
This controller handles the file upload, validates the media, and orchestrates the Task Tracker logic.

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Tonsoo\TaskTracker\AI\AiIntentAnalyzer;
use Tonsoo\TaskTracker\AI\Drivers\OpenAIDriver;
use Tonsoo\TaskTracker\Integrations\Trello\TrelloDriver;
use Tonsoo\TaskTracker\Services\Task\TaskOrchestrator;
use Tonsoo\TaskTracker\UseCases\ProcessIncomingMessage;

final class AudioTranscriptionController
{
    public function store(Request $request)
    {
        // 1. Validation: Ensure we have a valid WebM file within limits
        $request->validate([
            'audio' => 'required|file|mimetypes:audio/webm,video/webm|max:1024',
        ]);

        // 2. Capture Configuration from Headers
        // In a real app, you might fetch these from a User's database settings.
        $trelloInputs = [
            'key' => 'trello_key',
            'token' => 'trello_token',
            'board_id' => 'trello_board_id',
            'default_list_id' => 'trello_default_list_id',
        ];
        
        $openaiInputs = [
            'model' => 'openapi_model',
            'key' => 'openapi_key', 
        ];

        // 3. Transcription (Simple OpenAI Whisper implementation)
        // We convert the audio bytes into a plain text string.
        $transcript = $this->transcribe($request->file('audio'), $openaiInputs['key']);

        if (empty($transcript)) {
            return response()->json(['message' => 'No speech detected.'], 422);
        }

        // 4. Manual Driver Instantiation
        // We use the drivers to create the necessary Managers and Clients using the runtime inputs.
        $taskManager = (new TrelloDriver())->makeManager($$trelloInputs);
        $aiClient = (new OpenAIDriver())->makeClient($openaiInputs);

        // 5. Orchestrate the Task Tracker Workflow
        $analyzer = new AiIntentAnalyzer($aiClient);
        $orchestrator = new TaskOrchestrator($taskManager, $analyzer);

        // The Use Case handles the heavy lifting: Analyze Text -> Extract Intent -> Execute Task
        (new ProcessIncomingMessage($analyzer, $orchestrator))->handle($transcript);

        return response()->json([
            'status' => 'ok',
            'transcript' => $transcript
        ]);
    }

    /**
     * A simple wrapper to call OpenAI Whisper API directly.
     */
    private function transcribe($file, $apiKey): string
    {
        $response = Http::withToken($apiKey)
            ->attach('file', file_get_contents($file->getRealPath()), 'recording.webm')
            ->post('[https://api.openai.com/v1/audio/transcriptions](https://api.openai.com/v1/audio/transcriptions)', [
                'model' => 'whisper-1',
            ]);

        return $response->json('text', '');
    }
}
```