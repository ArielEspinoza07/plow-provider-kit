<?php

declare(strict_types=1);

use Plow\Detection\Composer\ComposerManifest;

test('merges psr-4 paths from autoload and autoload-dev', function (): void {
    $manifest = new ComposerManifest(
        autoloadPsr4: ['App\\' => 'src/'],
        autoloadDevPsr4: ['App\\Tests\\' => 'tests/'],
    );

    expect($manifest->psr4Paths())->toBe(['src', 'tests']);
});

test('flattens namespaces mapped to multiple paths', function (): void {
    $manifest = new ComposerManifest(
        autoloadPsr4: ['App\\' => ['src/', 'lib/']],
        autoloadDevPsr4: [],
    );

    expect($manifest->psr4Paths())->toBe(['src', 'lib']);
});

test('trims trailing slashes from each path', function (): void {
    $manifest = new ComposerManifest(
        autoloadPsr4: ['App\\' => 'src///'],
        autoloadDevPsr4: [],
    );

    expect($manifest->psr4Paths())->toBe(['src']);
});

test('deduplicates identical paths declared under different namespaces', function (): void {
    $manifest = new ComposerManifest(
        autoloadPsr4: ['App\\' => 'src/', 'App\\Other\\' => 'src/'],
        autoloadDevPsr4: [],
    );

    expect($manifest->psr4Paths())->toBe(['src']);
});

test('returns an empty array when nothing is autoloaded', function (): void {
    $manifest = new ComposerManifest(autoloadPsr4: [], autoloadDevPsr4: []);

    expect($manifest->psr4Paths())->toBe([]);
});
