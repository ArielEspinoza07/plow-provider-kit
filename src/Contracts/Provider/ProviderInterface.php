<?php

declare(strict_types=1);

namespace Plow\Contracts\Provider;

use Plow\Provider\Diagnostic\ProviderDiagnostic;
use Plow\Result\TaskResult;
use Plow\Task\Task;
use Plow\Task\TaskMode;

interface ProviderInterface
{
    public function name(): string;

    public function task(): Task;

    public function supports(Task $task): bool;

    public function isAvailable(): bool;

    public function execute(Task $task, TaskMode $mode): TaskResult;

    public function diagnose(): ProviderDiagnostic;

    public function composerPackage(): ?string;
}
