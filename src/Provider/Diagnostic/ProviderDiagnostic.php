<?php

declare(strict_types=1);

namespace Plow\Provider\Diagnostic;

final readonly class ProviderDiagnostic
{
    public function __construct(
        public string $name,
        public string $locatedAt,
        public bool $available,
    ) {}
}
