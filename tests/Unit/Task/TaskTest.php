<?php

declare(strict_types=1);

use Plow\Task\BuiltinTask;
use Plow\Task\Task;

test('fromString stores the given value', function (): void {
    $task = Task::fromString('custom-task');

    expect($task->value)->toBe('custom-task');
});

test('is a stringable that returns its value', function (): void {
    $task = Task::fromString('custom-task');

    expect((string) $task)->toBe('custom-task')
        ->and($task->__toString())->toBe('custom-task');
});

test('is() returns true only for a matching value', function (): void {
    $task = Task::fromString('custom-task');

    expect($task->is('custom-task'))->toBeTrue()
        ->and($task->is('other-task'))->toBeFalse();
});

test('equals() compares by value, not by instance', function (): void {
    $first = Task::fromString('custom-task');
    $second = Task::fromString('custom-task');
    $different = Task::fromString('other-task');

    expect($first->equals($second))->toBeTrue()
        ->and($first->equals($different))->toBeFalse();
});

test('named constructors map to the matching BuiltinTask value', function (BuiltinTask $builtin, Closure $makeTask): void {
    $task = $makeTask();

    expect($task->value)->toBe($builtin->value)
        ->and($task->is($builtin->value))->toBeTrue();
})->with([
    'format' => [BuiltinTask::Format, fn (): Task => Task::format()],
    'analyse' => [BuiltinTask::Analyse, fn (): Task => Task::analyse()],
    'test' => [BuiltinTask::Test, fn (): Task => Task::test()],
    'refactor' => [BuiltinTask::Refactor, fn (): Task => Task::refactor()],
    'audit' => [BuiltinTask::Audit, fn (): Task => Task::audit()],
]);

test('named constructors are equal to fromString() built from the same enum value', function (BuiltinTask $builtin, Closure $makeTask): void {
    expect($makeTask()->equals(Task::fromString($builtin->value)))->toBeTrue();
})->with([
    'format' => [BuiltinTask::Format, fn (): Task => Task::format()],
    'analyse' => [BuiltinTask::Analyse, fn (): Task => Task::analyse()],
    'test' => [BuiltinTask::Test, fn (): Task => Task::test()],
    'refactor' => [BuiltinTask::Refactor, fn (): Task => Task::refactor()],
    'audit' => [BuiltinTask::Audit, fn (): Task => Task::audit()],
]);
