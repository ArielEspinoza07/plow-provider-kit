<?php

declare(strict_types=1);

use Plow\Detection\Composer\ComposerManifestReader;
use Plow\Detection\ProjectPaths;
use Plow\Detection\ProjectRoot;

beforeEach(function (): void {
    $this->originalCwd = getcwd();
    $this->tempDirectory = createTempDirectory();
    chdir($this->tempDirectory);
});

afterEach(function (): void {
    chdir($this->originalCwd);
    deleteTempDirectory($this->tempDirectory);
});

test('returns the psr-4 paths declared in composer.json', function (): void {
    file_put_contents($this->tempDirectory.'/composer.json', json_encode([
        'autoload' => ['psr-4' => ['App\\' => 'src/']],
        'autoload-dev' => ['psr-4' => ['App\\Tests\\' => 'tests/']],
    ], JSON_THROW_ON_ERROR));

    $paths = new ProjectPaths(new ComposerManifestReader(new ProjectRoot));

    expect($paths->sourcePaths())->toBe(['src', 'tests']);
});

test('falls back to "src" when no psr-4 paths are declared', function (): void {
    file_put_contents($this->tempDirectory.'/composer.json', json_encode([], JSON_THROW_ON_ERROR));

    $paths = new ProjectPaths(new ComposerManifestReader(new ProjectRoot));

    expect($paths->sourcePaths())->toBe(['src']);
});
