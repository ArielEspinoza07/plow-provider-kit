<?php

declare(strict_types=1);

use Plow\Result\ResultStatus;
use Plow\Result\TaskResult;
use Plow\Task\Task;

function makeTaskResult(ResultStatus $status): TaskResult
{
    return new TaskResult(
        task: Task::test(),
        provider: 'pest',
        status: $status,
        output: 'out',
        errorOutput: 'err',
    );
}

test('exposes the values it was constructed with', function (): void {
    $task = Task::analyse();
    $result = new TaskResult(
        task: $task,
        provider: 'phpstan',
        status: ResultStatus::Passed,
        output: 'analysis output',
        errorOutput: '',
    );

    expect($result->task)->toBe($task)
        ->and($result->provider)->toBe('phpstan')
        ->and($result->status)->toBe(ResultStatus::Passed)
        ->and($result->output)->toBe('analysis output')
        ->and($result->errorOutput)->toBe('');
});

test('successful() delegates to the status', function (ResultStatus $status, bool $expected): void {
    expect(makeTaskResult($status)->successful())->toBe($expected);
})->with([
    'passed' => [ResultStatus::Passed, true],
    'skipped' => [ResultStatus::Skipped, true],
    'failed' => [ResultStatus::Failed, false],
    'error' => [ResultStatus::Error, false],
]);

test('exitCode() delegates to the status', function (ResultStatus $status, int $expected): void {
    expect(makeTaskResult($status)->exitCode())->toBe($expected);
})->with([
    'passed' => [ResultStatus::Passed, 0],
    'skipped' => [ResultStatus::Skipped, 0],
    'failed' => [ResultStatus::Failed, 1],
    'error' => [ResultStatus::Error, 1],
]);
