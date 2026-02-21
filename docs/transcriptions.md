# Meeting Transcriptions

This flow accepts meeting transcriptions via webhook, consolidates them, and triggers AI analysis to create tasks.

## Endpoint
- `POST /webhooks/transcribe/{meetingId}`
- Auth: Bearer token must match `TRANSCRIBER_SECRET_KEY`

## Expected Payload
```json
{
  "endedAt": "2025-12-19T12:34:56Z",
  "transcript": [
    { "timestamp": "2025-12-19T12:30:01Z", "text": "Text 1" },
    { "timestamp": "2025-12-19T12:30:08Z", "text": "Text 2" }
  ]
}
```

## How It Works
- The controller consolidates messages using `timestamp + text` as a de-duplication key
- New items are stored with `processed = false`
- Already-processed items remain `processed = true`
- When a meeting stays idle for a configured time, `task-tracker:monitor-idle` enqueues processing

## Scheduling
The command `task-tracker:monitor-idle` runs every 30 seconds via Laravel scheduler. Ensure your scheduler is active (cron calling `php artisan schedule:run`).

## Idempotency
- Already processed messages are not re-sent to the AI
- Prevents duplicate cards when the same meeting is processed multiple times

## Related Docs
- [Usage](usage.md)
- [AI Intent Analysis](ai.md)
