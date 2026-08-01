---
name: Bug report
about: Report a bug to help us improve Hephaestus
title: "[Bug] "
labels: bug
assignees: ''
---

## Description

A clear and concise description of the bug.

## Steps to reproduce

1. Create a command with `#[Command(...)]`
2. Run `...`
3. See error

## Expected behavior

What you expected to happen.

## Actual behavior

What actually happened. Include the full error message/stack trace if applicable.

```
paste error here
```

## Minimal reproduction

If possible, provide a minimal code snippet that reproduces the issue:

```php
<?php

use Plow\Contracts\Provider\ProviderInterface;

final class TestProvider implements ProviderInterface
{
    // ...
}
```

## Environment

| Key                       | Value             |
|---------------------------|-------------------|
| PHP version               | e.g. 8.3.0        |
| Plow provider kit version | e.g. 0.1.0        |
| OS                        | e.g. Ubuntu 24.04 |

## Additional context

Any other context about the problem here.