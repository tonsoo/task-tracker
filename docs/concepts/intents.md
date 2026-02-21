# Intents and Canonical Identity

The AI produces a `StructuredIntent`, which is the normalized representation of a task.

## StructuredIntent Fields
- `type`: `bug_report | feature_request | bug_fixed | unknown`
- `title`: short, technical, canonical title
- `description`: short summary
- `steps_to_reproduce`: optional steps list
- `tags`: keywords for filtering
- `canonical.object`: the core object being affected
- `canonical.action`: the core action or failure
- `resolution`: only used for `bug_fixed`

## Canonical Identity
Canonical identity is used for de-duplication. If two intents share the same `canonical.object` and `canonical.action`, they are treated as the same task.

### Example
Input:
```
App crashes when opening monthly report
Bug: export button does nothing when list has 100+ items
```

Possible canonical identities:
- Task 1: `object = "report"`, `action = "open_crash"`
- Task 2: `object = "export_button"`, `action = "no_response"`

## Choosing analyze vs analyzeFlexible
- Use `analyze()` when you **know** the input is one task.
- Use `analyzeFlexible()` for real-world inputs where the model should decide.

## Related Docs
- [AI Overview](../ai/overview.md)
- [Usage](usage.md)
