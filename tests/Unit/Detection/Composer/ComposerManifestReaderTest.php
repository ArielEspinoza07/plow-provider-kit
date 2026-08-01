<?php

declare(strict_types=1);

use Plow\Detection\Composer\ComposerManifestReader;
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

function writeComposerManifest(string $directory, array $contents): void
{
    file_put_contents($directory.'/composer.json', json_encode($contents, JSON_THROW_ON_ERROR));
}

test('reads psr-4 autoload paths from composer.json', function (): void {
    writeComposerManifest($this->tempDirectory, [
        'autoload' => ['psr-4' => ['App\\' => 'src/']],
        'autoload-dev' => ['psr-4' => ['App\\Tests\\' => 'tests/']],
    ]);

    $reader = new ComposerManifestReader(new ProjectRoot);

    expect($reader->read()->psr4Paths())->toBe(['src', 'tests']);
});

test('defaults to empty psr-4 maps when autoload sections are missing', function (): void {
    writeComposerManifest($this->tempDirectory, []);

    $reader = new ComposerManifestReader(new ProjectRoot);

    expect($reader->read()->psr4Paths())->toBe([]);
});

test('caches the parsed manifest across calls', function (): void {
    writeComposerManifest($this->tempDirectory, [
        'autoload' => ['psr-4' => ['App\\' => 'src/']],
    ]);

    $reader = new ComposerManifestReader(new ProjectRoot);
    $first = $reader->read();

    writeComposerManifest($this->tempDirectory, [
        'autoload' => ['psr-4' => ['App\\' => 'changed/']],
    ]);

    expect($reader->read())->toBe($first);
});

test('throws on malformed JSON', function (): void {
    file_put_contents($this->tempDirectory.'/composer.json', '{not valid json');

    $reader = new ComposerManifestReader(new ProjectRoot);

    expect(fn () => $reader->read())->toThrow(JsonException::class);
});
