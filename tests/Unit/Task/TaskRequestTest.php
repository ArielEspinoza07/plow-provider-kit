<?php

declare(strict_types=1);

use Plow\Task\Task;
use Plow\Task\TaskMode;
use Plow\Task\TaskRequest;

test('exposes the values it was constructed with', function (): void {
    $task = Task::format();
    $request = new TaskRequest(
        task: $task,
        mode: TaskMode::Apply,
        extraArguments: ['--dry-run', '--verbose'],
    );

    expect($request->task)->toBe($task)
        ->and($request->mode)->toBe(TaskMode::Apply)
        ->and($request->extraArguments)->toBe(['--dry-run', '--verbose']);
});

test('defaults to no mode and no extra arguments', function (): void {
    $request = new TaskRequest(task: Task::format());

    expect($request->mode)->toBeNull()
        ->and($request->extraArguments)->toBe([]);
});
