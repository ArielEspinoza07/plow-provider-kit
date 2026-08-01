# Security Policy

## Supported versions

| Version | Supported |
|---------|-----------|
| 0.1.x   | ✅ Yes    |

This package is currently pre-1.0. Only the latest published release receives security fixes until
`1.0.0` ships, at which point this table will track the latest minor release of the current major
version.

## Reporting a vulnerability

**Please do not report security vulnerabilities through public GitHub issues.**

If you discover a security vulnerability in Plow Provider Kit, please disclose it responsibly by emailing:

**arielespinoza46@gmail.com**

Include as much of the following as possible:

- A description of the vulnerability and its potential impact
- Steps to reproduce or a proof-of-concept
- Affected versions
- Any suggested fix (optional)

You will receive an acknowledgement within **48 hours** and a resolution timeline within **7 days**.

## Scope

Plow Provider Kit is a small, framework-agnostic library: contracts, value objects, and detection
helpers used by [Plow](https://github.com/ArielEspinoza07/plow) providers. It does not execute
commands, load classes dynamically, deserialize data, or parse attributes — so most of the classic
library-level vulnerability classes simply don't apply here. Vulnerabilities most relevant to this
package are limited to:

- Directory-traversal or resolution bugs in `Plow\Detection\ProjectRoot` that could cause it to
  resolve to an unintended directory
- Unsafe parsing of the consuming project's `composer.json` in
  `Plow\Detection\Composer\ComposerManifestReader` / `ComposerManifest`
- Type or contract defects in the public value objects (`Task`, `TaskResult`, `ResultStatus`,
  `ProcessResult`, `ProviderDiagnostic`) that could cause a provider to misreport its status or result

Out of scope:

- How a concrete `Plow\Contracts\Provider\ProviderInterface` implementation (a separate provider
  package) invokes the external tool it wraps — this package only defines the contract, it never
  shells out itself
- How a concrete `Plow\Contracts\Execution\ProcessRunnerInterface` implementation runs processes —
  again, this package only defines the contract
- How the Plow core application (a separate repository) discovers, trusts, or executes providers

We appreciate reports on out-of-scope issues too, but they may be redirected to the relevant
repository.

## Disclosure policy

Once a fix is released, the vulnerability will be disclosed publicly via a GitHub Security Advisory
with full credit to the reporter (unless you prefer to remain anonymous).
