<?php

declare(strict_types=1);

use Plow\Contracts\Execution\ProcessRunnerInterface;
use Plow\Execution\ProcessResult;
use Plow\Tests\Fixtures\Execution\FakeProcessRunner;

test('a runner returns the process result it was configured with', function (): void {
    $result = new ProcessResult(exitCode: 0, output: 'done', errorOutput: '');
    $runner = new FakeProcessRunner($result);

    expect($runner)->toBeInstanceOf(ProcessRunnerInterface::class)
        ->and($runner->run(['echo', 'hi']))->toBe($result);
});

test('a runner receives the command and working directory it is called with', function (): void {
    $runner = new FakeProcessRunner;

    $runner->run(['vendor/bin/pint', '--test'], workingDirectory: '/var/www/app');

    expect($runner->receivedCommand)->toBe(['vendor/bin/pint', '--test'])
        ->and($runner->receivedWorkingDirectory)->toBe('/var/www/app');
});

test('a runner forwards output to the given callback', function (): void {
    $runner = new FakeProcessRunner;
    $received = [];

    $runner->run(['echo', 'hi'], onOutput: function (string $line) use (&$received): void {
        $received[] = $line;
    });

    expect($received)->toBe(['fake output line']);
});
