<?php

declare(strict_types=1);

use Plow\Detection\ProjectRoot;

beforeEach(function (): void {
    $this->originalCwd = getcwd();
    $this->tempDirectory = createTempDirectory();
});

afterEach(function (): void {
    chdir($this->originalCwd);
    deleteTempDirectory($this->tempDirectory);
});

test('resolves the directory containing composer.json', function (): void {
    file_put_contents($this->tempDirectory.'/composer.json', '{}');
    chdir($this->tempDirectory);

    $path = (new ProjectRoot)->path();

    expect(realpath($path))->toBe(realpath($this->tempDirectory));
});

test('walks up parent directories to find composer.json', function (): void {
    file_put_contents($this->tempDirectory.'/composer.json', '{}');
    $nested = $this->tempDirectory.'/a/b/c';
    mkdir($nested, recursive: true);
    chdir($nested);

    $path = (new ProjectRoot)->path();

    expect(realpath($path))->toBe(realpath($this->tempDirectory));
});

test('caches the resolved path across calls', function (): void {
    file_put_contents($this->tempDirectory.'/composer.json', '{}');
    chdir($this->tempDirectory);

    $projectRoot = new ProjectRoot;
    $first = $projectRoot->path();

    // Moving elsewhere afterwards must not change the cached result.
    chdir($this->originalCwd);
    $second = $projectRoot->path();

    expect($second)->toBe($first);
});

test('throws when no composer.json exists up to the filesystem root', function (): void {
    $directory = $this->tempDirectory;
    while (! is_file($directory.'/composer.json')) {
        $parent = dirname($directory);
        if ($parent === $directory) {
            break;
        }
        $directory = $parent;
    }

    if (is_file($directory.'/composer.json')) {
        $this->markTestSkipped('A composer.json exists above the temp directory in this environment.');
    }

    chdir($this->tempDirectory);

    expect(fn () => (new ProjectRoot)->path())->toThrow(RuntimeException::class);
});
