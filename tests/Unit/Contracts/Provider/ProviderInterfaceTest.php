<?php

declare(strict_types=1);

use Plow\Contracts\Provider\ProviderInterface;
use Plow\Execution\ProcessResult;
use Plow\Provider\Diagnostic\ProviderDiagnostic;
use Plow\Result\ResultStatus;
use Plow\Result\TaskResult;
use Plow\Task\Task;
use Plow\Task\TaskMode;
use Plow\Task\TaskRequest;
use Plow\Tests\Fixtures\Execution\FakeProcessRunner;
use Plow\Tests\Fixtures\Provider\FakeProvider;

test('exposes its name and task', function (): void {
    $provider = new FakeProvider(name: 'pint', task: Task::format());

    expect($provider)->toBeInstanceOf(ProviderInterface::class)
        ->and($provider->name())->toBe('pint')
        ->and($provider->task())->toEqual(Task::format());
});

test('supports() is true only for its own task', function (): void {
    $provider = new FakeProvider(name: 'pint', task: Task::format());

    expect($provider->supports(Task::format()))->toBeTrue()
        ->and($provider->supports(Task::test()))->toBeFalse();
});

test('isAvailable() reflects the configured availability', function (): void {
    $available = new FakeProvider(name: 'pint', task: Task::format(), available: true);
    $unavailable = new FakeProvider(name: 'pint', task: Task::format(), available: false);

    expect($available->isAvailable())->toBeTrue()
        ->and($unavailable->isAvailable())->toBeFalse();
});

test('execute() records the request it was called with', function (): void {
    $provider = new FakeProvider(name: 'pint', task: Task::format());
    $request = new TaskRequest(task: Task::format(), mode: TaskMode::Apply);

    $result = $provider->execute($request);

    expect($result)->toBeInstanceOf(TaskResult::class)
        ->and($provider->lastExecutedRequest)->toBe($request);
});

test('execute() runs the process runner and builds a result from its output', function (): void {
    $processResult = new ProcessResult(exitCode: 0, output: 'formatted 3 files', errorOutput: '');
    $processRunner = new FakeProcessRunner($processResult);
    $provider = new FakeProvider(name: 'pint', task: Task::format(), processRunner: $processRunner);

    $result = $provider->execute(new TaskRequest(task: Task::format(), mode: TaskMode::Apply));

    expect($processRunner->receivedCommand)->toBe(['pint', 'format'])
        ->and($result->status)->toBe(ResultStatus::Passed)
        ->and($result->output)->toBe('formatted 3 files')
        ->and($result->errorOutput)->toBe('');
});

test('execute() appends the request\'s extra arguments to the command', function (): void {
    $processRunner = new FakeProcessRunner;
    $provider = new FakeProvider(name: 'pint', task: Task::format(), processRunner: $processRunner);

    $provider->execute(new TaskRequest(
        task: Task::format(),
        mode: TaskMode::Apply,
        extraArguments: ['--dirty', '--test'],
    ));

    expect($processRunner->receivedCommand)->toBe(['pint', 'format', '--dirty', '--test']);
});

test('execute() maps a failing process result to a failed status', function (): void {
    $processResult = new ProcessResult(exitCode: 1, output: '', errorOutput: 'syntax error');
    $processRunner = new FakeProcessRunner($processResult);
    $provider = new FakeProvider(name: 'pint', task: Task::format(), processRunner: $processRunner);

    $result = $provider->execute(new TaskRequest(task: Task::format(), mode: TaskMode::Apply));

    expect($result->status)->toBe(ResultStatus::Failed)
        ->and($result->errorOutput)->toBe('syntax error');
});

test('execute() returns the configured result when given', function (): void {
    $configured = new TaskResult(
        task: Task::format(),
        provider: 'pint',
        status: ResultStatus::Failed,
        output: '',
        errorOutput: 'boom',
    );
    $processRunner = new FakeProcessRunner;
    $provider = new FakeProvider(
        name: 'pint',
        task: Task::format(),
        result: $configured,
        processRunner: $processRunner,
    );

    expect($provider->execute(new TaskRequest(task: Task::format(), mode: TaskMode::DryRun)))->toBe($configured)
        ->and($processRunner->receivedCommand)->toBeNull();
});

test('diagnose() returns a diagnostic reflecting the provider state', function (): void {
    $provider = new FakeProvider(
        name: 'pint',
        task: Task::format(),
        available: true,
        composerPackage: 'laravel/pint',
    );

    $diagnostic = $provider->diagnose();

    expect($diagnostic)->toBeInstanceOf(ProviderDiagnostic::class)
        ->and($diagnostic->name)->toBe('pint')
        ->and($diagnostic->locatedAt)->toBe('laravel/pint')
        ->and($diagnostic->available)->toBeTrue();
});

test('composerPackage() can be null for non-package providers', function (): void {
    $provider = new FakeProvider(name: 'custom', task: Task::format());

    expect($provider->composerPackage())->toBeNull();
});
