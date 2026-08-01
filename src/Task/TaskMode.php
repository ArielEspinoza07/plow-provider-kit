<?php

declare(strict_types=1);

namespace Plow\Task;

enum TaskMode
{
    case DryRun;
    case Apply;
}
