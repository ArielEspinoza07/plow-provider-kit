<?php

declare(strict_types=1);

namespace Plow\Task;

enum BuiltinTask: string
{
    case Analyse = 'analyse';
    case Audit = 'audit';
    case Format = 'format';
    case Refactor = 'refactor';
    case Test = 'test';
}
