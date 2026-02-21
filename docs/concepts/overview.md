# Concepts Overview

Task Tracker is built around a small set of concepts: **messages**, **intents**, **tasks**, and **drivers**. Understanding these makes everything else straightforward.

## Core Concepts
- **Messaging driver**: Parses a platform payload into `IncomingMessage` items
- **Intent analyzer**: Uses an LLM to turn text into `StructuredIntent`
- **Task manager**: Creates/updates tasks in your chosen system
- **Orchestrator**: Applies business rules (create/update/close)

## The Flow
1. A webhook receives a payload
2. The messaging driver parses it to `IncomingMessage`
3. The AI extracts one or more `StructuredIntent` objects
4. The orchestrator de-duplicates and applies task actions

## Key Classes
- `Tonsoo\TaskTracker\Messaging\Contracts\MessagingDriver`
- `Tonsoo\TaskTracker\AI\AiIntentAnalyzer`
- `Tonsoo\TaskTracker\Contracts\TaskManager`
- `Tonsoo\TaskTracker\Services\Task\TaskOrchestrator`

## Where to Go Next
- [Configuration](configuration.md)
- [Usage](usage.md)
- [AI Overview](../ai/overview.md)
- [Task Managers](../drivers/task-managers.md)
