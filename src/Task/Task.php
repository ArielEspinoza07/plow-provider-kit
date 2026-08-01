<?php

declare(strict_types=1);

namespace Plow\Task;

use Stringable;

final readonly class Task implements Stringable
{
    private function __construct(public string $value) {}

    public function __toString(): string
    {
        return $this->value;
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public static function format(): self
    {
        return self::fromString(BuiltinTask::Format->value);
    }

    public static function analyse(): self
    {
        return self::fromString(BuiltinTask::Analyse->value);
    }

    public static function test(): self
    {
        return self::fromString(BuiltinTask::Test->value);
    }

    public static function refactor(): self
    {
        return self::fromString(BuiltinTask::Refactor->value);
    }

    public static function audit(): self
    {
        return self::fromString(BuiltinTask::Audit->value);
    }

    public function is(string $value): bool
    {
        return $this->value === $value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
