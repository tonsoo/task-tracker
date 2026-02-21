# Overview

Task Tracker receives incoming messages (WhatsApp by default), extracts structured intent using AI, and performs actions in your configured task manager (Trello by default). It is distributed as a Laravel package and plugs in through a Service Provider, routes, and a publishable config file.

## What It Does
- Listens to messaging webhooks and stores incoming messages
- Uses an LLM to extract structured task intents
- De-duplicates or updates existing tasks
- Creates new tasks when needed

## Key Files
- Namespace: `Tonso\TaskTracker\`
- Entry Provider: `src/TaskTrackerServiceProvider.php`
- Routes: `routes/api.php`
- Config: `config/task-tracker.php`

## High-Level Flow
1. Incoming webhook hits `POST /webhooks/messaging/{driver}`
2. The driver parses the payload into `IncomingMessage` items
3. Each message is processed by `ProcessIncomingMessage`
4. The AI produces `StructuredIntent` items
5. `TaskOrchestrator` decides whether to create/update/close tasks

## Where to Go Next
- [Installation](installation.md)
- [Configuration](configuration.md)
- [Usage](usage.md)
