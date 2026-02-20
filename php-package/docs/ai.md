# Análise de Intenção por IA

- Analyzer: `Tonso\TaskTracker\AI\AiIntentAnalyzer`
- Contrato do cliente: `Tonso\TaskTracker\AI\Contracts\LLMClient`
- Cliente padrão: `Tonso\TaskTracker\AI\Clients\OpenAILLMClient`

## Extração de intenção
`AiIntentAnalyzer::analyze($message)` monta um prompt estruturado e espera JSON como retorno. Devolve `StructuredIntent` com:
- `type`: `bug_report | feature_request | bug_fixed | unknown`
- `title`
- `description` (pode ser nulo)
- `steps_to_reproduce` (array)
- `tags` (array)
- `resolution` (pode ser nulo)
- `canonical` (objeto com `object` e `action`)

## Checagem de similaridade
`AiIntentAnalyzer::findMatchInBatch($newIntent, $candidates)` retorna `{ match, task_id, confidence, reason }`, usado pelo `TaskOrchestrator` para decidir se atualiza uma tarefa existente.

## Configuração
- Driver via `ai.driver`
- Modelo e chave via `ai.drivers.openai.*`
- Limiares: `ai.similarity_threshold`
