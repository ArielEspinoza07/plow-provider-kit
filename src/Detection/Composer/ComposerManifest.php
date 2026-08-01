<?php

declare(strict_types=1);

namespace Plow\Detection\Composer;

final readonly class ComposerManifest
{
    /**
     * @param  array<string, string|string[]>  $autoloadPsr4
     * @param  array<string, string|string[]>  $autoloadDevPsr4
     */
    public function __construct(
        private array $autoloadPsr4,
        private array $autoloadDevPsr4,
    ) {}

    /** @return string[] */
    public function psr4Paths(): array
    {
        $paths = [];

        foreach ([$this->autoloadPsr4, $this->autoloadDevPsr4] as $map) {
            foreach ($map as $value) {
                foreach ((array) $value as $path) {
                    $paths[] = rtrim($path, '/');
                }
            }
        }

        return array_values(array_unique($paths));
    }
}
