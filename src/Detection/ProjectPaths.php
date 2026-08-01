<?php

declare(strict_types=1);

namespace Plow\Detection;

use Plow\Detection\Composer\ComposerManifestReader;

final readonly class ProjectPaths
{
    public function __construct(private ComposerManifestReader $manifestReader) {}

    /** @return string[] */
    public function sourcePaths(): array
    {
        $paths = $this->manifestReader->read()->psr4Paths(); // reads composer.json autoload + autoload-dev

        return $paths !== [] ? $paths : ['src'];
    }
}
