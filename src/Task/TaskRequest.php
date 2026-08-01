<?php

declare(strict_types=1);

namespace Plow\Task;

final readonly class TaskRequest
{
    /** @param list<string> $extraArguments */
    public function __construct(
        public Task $task,
        public TaskMode $mode = TaskMode::Apply,
        public array $extraArguments = [],
    ) {}
}
