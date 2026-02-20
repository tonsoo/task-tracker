# Transcricoes de Reunioes

Este fluxo recebe transcricoes por webhook, consolida o texto e dispara a analise por IA para gerar cards no Trello.

## Endpoint
- POST `/webhooks/transcribe/{meetingId}`
- Autenticacao: Bearer token igual a `TRANSCRIBER_SECRET_KEY`

## Payload esperado
```json
{
  "endedAt": "2025-12-19T12:34:56Z",
  "transcript": [
    { "timestamp": "2025-12-19T12:30:01Z", "text": "Texto 1" },
    { "timestamp": "2025-12-19T12:30:08Z", "text": "Texto 2" }
  ]
}
```

## Como funciona
- O controller consolida as mensagens usando `timestamp` + `text` como chave de deduplicacao.
- Itens novos entram com `processed = false`. Itens ja processados permanecem com `processed = true`.
- Quando um meeting fica inativo por X minutos, o comando `trello-tracker:monitor-idle` enfileira o processamento.

## Agendamento
O comando `trello-tracker:monitor-idle` roda a cada 30 segundos via scheduler do Laravel. Garanta que o scheduler esteja ativo (cron chamando `php artisan schedule:run`).

## Comportamento de idempotencia
- Mensagens ja processadas nao sao reenviadas para a IA.
- Isso evita criacao de cards duplicados quando o mesmo meeting e processado mais de uma vez.
