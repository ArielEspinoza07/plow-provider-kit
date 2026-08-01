<?php

declare(strict_types=1);

namespace Plow\Tests\Fixtures\Provider;

use Plow\Contracts\Provider\ProviderInterface;
use Plow\Provider\Diagnostic\ProviderDiagnostic;
use Plow\Result\ResultStatus;
use Plow\Result\TaskResult;
use Plow\Task\Task;
use Plow\Task\TaskRequest;
use Plow\Tests\Fixtures\Execution\FakeProcessRunner;

final class FakeProvider implements ProviderInterface
{
    public ?TaskRequest $lastExecutedRequest = null;

    public function __construct(
        private readonly string $name,
        private readonly Task $task,
        private readonly bool $available = true,
        private readonly ?TaskResult $result = null,
        private readonly ?string $composerPackage = null,
        public readonly FakeProcessRunner $processRunner = new FakeProcessRunner,
    ) {}

    public function name(): string
    {
        return $this->name;
    }

    public function task(): Task
    {
        return $this->task;
    }

    public function supports(Task $task): bool
    {
        return $this->task->equals($task);
    }

    public function isAvailable(): bool
    {
        return $this->available;
    }

    public function execute(TaskRequest $request): TaskResult
    {
        $this->lastExecutedRequest = $request;

        if ($this->result !== null) {
            return $this->result;
        }

        $command = [$this->name, (string) $request->task, ...$request->extraArguments];
        $processResult = $this->processRunner->run($command);

        return new TaskResult(
            task: $request->task,
            provider: $this->name,
            status: $processResult->successful() ? ResultStatus::Passed : ResultStatus::Failed,
            output: $processResult->output,
            errorOutput: $processResult->errorOutput,
        );
    }

    public function diagnose(): ProviderDiagnostic
    {
        return new ProviderDiagnostic(
            name: $this->name,
            locatedAt: $this->composerPackage ?? '',
            available: $this->available,
        );
    }

    public function composerPackage(): ?string
    {
        return $this->composerPackage;
    }
}
