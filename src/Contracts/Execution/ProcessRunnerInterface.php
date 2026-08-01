<?php

declare(strict_types=1);

namespace Plow\Contracts\Execution;

use Plow\Execution\ProcessResult;
use Closure;

interface ProcessRunnerInterface
{
    /**
     * @param  list<string>  $command
     */
    public function run(
        array $command,
        ?string $workingDirectory = null,
        ?Closure $onOutput = null,
    ): ProcessResult;
}
