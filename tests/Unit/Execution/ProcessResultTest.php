<?php

declare(strict_types=1);

use Plow\Execution\ProcessResult;

test('exposes the values it was constructed with', function (): void {
    $result = new ProcessResult(exitCode: 0, output: 'done', errorOutput: '');

    expect($result->exitCode)->toBe(0)
        ->and($result->output)->toBe('done')
        ->and($result->errorOutput)->toBe('');
});

test('successful() is true only when the exit code is zero', function (int $exitCode, bool $expected): void {
    $result = new ProcessResult(exitCode: $exitCode, output: '', errorOutput: '');

    expect($result->successful())->toBe($expected);
})->with([
    'zero' => [0, true],
    'one' => [1, false],
    'negative' => [-1, false],
    'arbitrary exit code' => [127, false],
]);
