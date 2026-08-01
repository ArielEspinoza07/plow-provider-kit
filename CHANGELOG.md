# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.1.0] - 2026-08-01

### Added

- `Plow\Task\Task`, `Plow\Task\BuiltinTask`, `Plow\Task\TaskMode` — the task a provider runs and the
  mode it runs in (apply or dry-run).
- `Plow\Task\TaskRequest` — what a provider's `execute()` receives: the task, its mode, and any extra
  arguments to forward to the underlying tool.
- `Plow\Result\TaskResult`, `Plow\Result\ResultStatus` — the outcome a provider reports after running
  a task.
- `Plow\Provider\Diagnostic\ProviderDiagnostic` — the health-check information a provider exposes for
  `plow doctor`.
- `Plow\Execution\ProcessResult` — the outcome of running an external process.
- `Plow\Detection\ProjectRoot`, `Plow\Detection\ProjectPaths` — locating the consuming project's root
  directory and its declared PSR-4 source paths.
- `Plow\Detection\Composer\ComposerManifest`, `Plow\Detection\Composer\ComposerManifestReader` —
  reading and parsing the consuming project's `composer.json`.
- `Plow\Contracts\Provider\ProviderInterface` — the interface every Plow provider implements, with
  `execute(TaskRequest $request): TaskResult`.
- `Plow\Contracts\Execution\ProcessRunnerInterface` — the contract for running the external process a
  provider wraps.
- Full Pest test suite for the above, including fixtures (`FakeProcessRunner`, `FakeProvider`) for
  contract-level testing.

[Unreleased]: https://github.com/arielespinoza07/plow-provider-kit/compare/v0.1.0...HEAD
[0.1.0]: https://github.com/arielespinoza07/plow-provider-kit/releases/tag/v0.1.0
