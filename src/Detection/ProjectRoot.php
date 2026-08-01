<?php

declare(strict_types=1);

namespace Plow\Detection;

use RuntimeException;

final class ProjectRoot
{
    private ?string $path = null;

    public function path(): string
    {
        if ($this->path !== null) {
            return $this->path;
        }

        $directory = getcwd();
        if ($directory === false) {
            throw new RuntimeException('Unable to determine the current working directory.');
        }

        while (! is_file($directory.'/composer.json')) {
            $parent = dirname($directory);

            if ($parent === $directory) {
                throw new RuntimeException(
                    'Could not locate a composer.json in the current directory or any parent directory.'
                );
            }

            $directory = $parent;
        }

        return $this->path = $directory;
    }
}
