<?php

declare(strict_types=1);

namespace Plow\Detection\Composer;

use Plow\Detection\ProjectRoot;
use RuntimeException;

final class ComposerManifestReader
{
    private ?ComposerManifest $manifest = null;

    public function __construct(private readonly ProjectRoot $projectRoot) {}

    public function read(): ComposerManifest
    {
        if ($this->manifest !== null) {
            return $this->manifest;
        }

        $path = $this->projectRoot->path().'/composer.json';
        $contents = file_get_contents($path);

        if ($contents === false) {
            throw new RuntimeException("Unable to read composer.json at \"{$path}\".");
        }

        /** @var array{autoload?: array{'psr-4'?: array<string, string|string[]>}, 'autoload-dev'?: array{'psr-4'?: array<string, string|string[]>}} $decoded */
        $decoded = json_decode($contents, associative: true, flags: JSON_THROW_ON_ERROR);

        return $this->manifest = new ComposerManifest(
            autoloadPsr4: $decoded['autoload']['psr-4'] ?? [],
            autoloadDevPsr4: $decoded['autoload-dev']['psr-4'] ?? [],
        );
    }
}
