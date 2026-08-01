<?php

declare(strict_types=1);

use Plow\Provider\Diagnostic\ProviderDiagnostic;

test('exposes the values it was constructed with', function (): void {
    $diagnostic = new ProviderDiagnostic(
        name: 'pint',
        locatedAt: '/usr/local/bin/pint',
        available: true,
    );

    expect($diagnostic->name)->toBe('pint')
        ->and($diagnostic->locatedAt)->toBe('/usr/local/bin/pint')
        ->and($diagnostic->available)->toBeTrue();
});

test('can represent an unavailable provider', function (): void {
    $diagnostic = new ProviderDiagnostic(name: 'pint', locatedAt: '', available: false);

    expect($diagnostic->available)->toBeFalse();
});
