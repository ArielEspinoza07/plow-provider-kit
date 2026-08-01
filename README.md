# Plow Provider Kit

[![CI](https://github.com/arielespinoza07/plow-provider-kit/actions/workflows/ci.yml/badge.svg)](https://github.com/arielespinoza07/plow-provider-kit/actions/workflows/ci.yml)
[![Latest Version](https://img.shields.io/packagist/v/arielespinoza07/plow-provider-kit.svg)](https://packagist.org/packages/arielespinoza07/plow-provider-kit)
[![Total Downloads](https://img.shields.io/packagist/dt/arielespinoza07/plow-provider-kit.svg)](https://packagist.org/packages/arielespinoza07/plow-provider-kit)
[![PHP Version](https://img.shields.io/packagist/php-v/arielespinoza07/plow-provider-kit.svg)](https://packagist.org/packages/arielespinoza07/plow-provider-kit)
[![License](https://img.shields.io/packagist/l/arielespinoza07/plow-provider-kit.svg)](LICENSE)

The minimal dependency surface for building a [Plow](https://github.com/ArielEspinoza07/plow) provider — the interfaces, value objects, and execution helpers a provider needs, and nothing else.

Plow orchestrates existing PHP development tools (formatters, static analyzers, test runners, and more) behind one consistent command interface. A **provider** is the adapter between Plow and a specific tool — Pint, PHPStan, Pest, and so on are all providers. This package is what you depend on to write your own.


---

## Requirements

- PHP 8.3+

---

## Installation

```bash
composer require arielespinoza07/plow-provider-kit
```

Your provider package should depend on **this package only** — not on `arielespinoza07/plow` itself. That keeps your package lightweight and avoids a circular dependency between your provider and the application that loads it.


---

## What's included

- **`Plow\Contracts\Provider\ProviderInterface`** — the interface every provider implements.
- **`Plow\Contracts\Execution\ProcessRunnerInterface`** — for running external processes (the underlying tool your provider wraps).
- **`Plow\Task\Task`** / **`Plow\Task\TaskMode`** — what task is being run, and in what mode (apply or dry-run).
- **`Plow\Task\TaskRequest`** — what `ProviderInterface::execute()` receives: the task, its mode, and any extra arguments.
- **`Plow\Result\TaskResult`** / **`Plow\Result\ResultStatus`** — what your provider returns after running.
- **`Plow\Execution\ProcessResult`** — the outcome of a process run by a `ProcessRunnerInterface` implementation.
- **`Plow\Detection\ProjectRoot`** / **`Plow\Detection\ProjectPaths`** — locating the consuming project's root and source paths, for resolving binaries and analysis targets.
- **`Plow\Provider\Diagnostic\ProviderDiagnostic`** — the health-check info `plow doctor` displays for your provider.


---

## Writing a provider

```php
<?php

declare(strict_types=1);

namespace Acme\PlowEslintBridge;

use Plow\Contracts\Provider\ProviderInterface;
use Plow\Contracts\Execution\ProcessRunnerInterface;
use Plow\Detection\ProjectRoot;
use Plow\Provider\Diagnostic\ProviderDiagnostic;
use Plow\Result\{TaskResult, ResultStatus};
use Plow\Task\{Task, TaskMode, TaskRequest};

final readonly class EslintProvider implements ProviderInterface
{
    public function __construct(
        private ProcessRunnerInterface $processRunner,
        private ProjectRoot $projectRoot,
    ) {}

    public function name(): string
    {
        return 'eslint';
    }

    public function task(): Task
    {
        return Task::fromString('lint-js');
    }

    public function supports(Task $task): bool
    {
        return $task->equals($this->task());
    }

    public function composerPackage(): ?string
    {
        return null; // installed via npm, not Composer
    }

    public function isAvailable(): bool
    {
        return is_file($this->projectRoot->path() . '/node_modules/.bin/eslint');
    }

    public function diagnose(): ProviderDiagnostic
    {
        return new ProviderDiagnostic(
            name: $this->name(),
            locatedAt: $this->projectRoot->path() . '/node_modules/.bin/eslint',
            available: $this->isAvailable(),
        );
    }

    public function execute(TaskRequest $request): TaskResult
    {
        $command = [$this->projectRoot->path() . '/node_modules/.bin/eslint', '.', ...$request->extraArguments];
        if ($request->mode === TaskMode::Apply) {
            $command[] = '--fix';
        }

        $result = $this->processRunner->run($command, $this->projectRoot->path());

        return new TaskResult(
            task: $request->task,
            provider: $this->name(),
            status: $result->successful() ? ResultStatus::Passed : ResultStatus::Failed,
            output: $result->output,
            errorOutput: $result->errorOutput,
        );
    }
}
```

---

## Registering your provider with Plow

Add a `"type"` and an `extra.plow.providers` entry to your own package's `composer.json`:

```json
{
    "name": "acme/plow-eslint-bridge",
    "type": "plow-provider",
    "require": {
        "arielespinoza07/plow-provider-kit": "^0.1"
    },
    "extra": {
        "plow": {
            "providers": ["Acme\\PlowEslintBridge\\EslintProvider"]
        }
    }
}
```

Plow discovers installed packages of type `plow-provider` automatically. For safety, a newly discovered provider isn't loaded until the person using Plow explicitly trusts it — either by running `plow init` (which prompts for any newly-discovered packages) or by adding it manually to `plow.php`:

```php
return PlowConfig::configure()
    ->trustProvider('acme/plow-eslint-bridge')
    // ...
```

---

## Compatibility

Requires PHP 8.3+. This package is currently pre-1.0 (`0.x`) — the public API may still change between
minor releases while the provider contracts settle. Once `1.0.0` ships, a breaking change to any
interface or value object will be released as a new major version, per semantic versioning.

---

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for setup instructions, code conventions, and PR guidelines.

---

## License

[MIT License](LICENSE)