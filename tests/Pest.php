<?php

declare(strict_types=1);

/**
 * Creates an isolated temporary directory for filesystem-dependent Detection tests.
 */
function createTempDirectory(): string
{
    $path = sys_get_temp_dir().DIRECTORY_SEPARATOR.'plow-'.bin2hex(random_bytes(8));

    mkdir($path, recursive: true);

    return $path;
}

/**
 * Recursively removes a directory created by createTempDirectory().
 */
function deleteTempDirectory(string $path): void
{
    if (! is_dir($path)) {
        return;
    }

    foreach (scandir($path) as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }

        $itemPath = $path.DIRECTORY_SEPARATOR.$item;
        is_dir($itemPath) ? deleteTempDirectory($itemPath) : unlink($itemPath);
    }

    rmdir($path);
}
