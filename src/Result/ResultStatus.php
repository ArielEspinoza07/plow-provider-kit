<?php

declare(strict_types=1);

namespace Plow\Result;

enum ResultStatus
{
    case Error;
    case Failed;
    case Passed;
    case Skipped;

    public function successful(): bool
    {
        return match ($this) {
            self::Passed, self::Skipped => true,
            self::Failed, self::Error => false,
        };
    }

    // Matches the Unix/Hephaestus SUCCESS=0, FAILURE=1 convention.
    // Not coupled to Hephaestus directly — Result stays framework-agnostic —
    // but relies on that convention holding. Worth revisiting if Hephaestus
    // ever adds a third meaningful exit code.
    public function exitCode(): int
    {
        return $this->successful() ? 0 : 1;
    }
}
