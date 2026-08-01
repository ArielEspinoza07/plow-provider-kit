<?php

declare(strict_types=1);

namespace Plow\Result;

use Plow\Task\Task;

final readonly class TaskResult
{
    public function __construct(
        public Task $task,
        public string $provider,
        public ResultStatus $status,
        public string $output,
        public string $errorOutput,
    ) {}

    public function successful(): bool
    {
        return $this->status->successful();
    }

    public function exitCode(): int
    {
        return $this->status->exitCode();
    }
}
