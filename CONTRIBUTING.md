# Contributing to `cron-job-api`

## Code of Conduct

We expect everyone to be respectful, welcoming, and professional. This project follows the spirit of the Contributor Covenant v2.1. Report unacceptable behavior to the maintainer at <kapersoft@gmail.com>.

## Getting Started

- Clone: `git clone https://github.com/kapersoft/cron-job-api.git && cd cron-job-api`
- Install tools: PHP 8.4+, Composer 2+
- Install deps: `composer install`
- Verify: `composer test` and `composer lint`

## Development Setup

- PHP: 8.4+
- Composer: 2+
- Optional: Xdebug for debugging
- No external services required; tests use mocked HTTP via Guzzle

Handy scripts:

- Check everything: `composer lint`
- Auto-fix style/refactors: `composer format`
- Run tests: `composer test`

Raw tool equivalents (if you prefer):

- Rector (dry-run): `vendor/bin/rector process --dry-run`
- Rector (apply): `vendor/bin/rector process`
- Pint (check): `vendor/bin/pint --test`
- Pint (fix): `vendor/bin/pint --repair`
- PHPStan: `vendor/bin/phpstan analyse`
- Composer normalize (check): `composer normalize --dry-run`

## Coding Standards

- Style is enforced by Pint (preset: Laravel) with custom rules in `pint.json`.
- Automated refactors via Rector (`rector.php`). Prefer safe rules and minimal diffs.
- Static analysis via PHPStan (level 5) as configured in `phpstan.neon`.
- Type-safety: strict types are enabled; prefer explicit types, early returns, and clear names.
- Keep classes and methods `final` where practical (see Pint rules).
- Run `composer format` before pushing. CI expects code to be formatted and analyzers to pass.

## Testing

- Full suite: `composer test`
- Single test: `vendor/bin/phpunit --filter YourTestOrMethod`
- PHPUnit config: `phpunit.xml` (fails on warnings/deprecations; source includes `src/`).

Guidelines:

- Add tests for new features and bug fixes.
- Tests should not depend on network; use Guzzle mocks like in `tests/ClientTest.php`.

## Pull Request Process

1. Create a feature branch: `git switch -c feat/your-topic`
2. Make focused commits with clear messages.
3. Run locally: `composer format && composer lint && composer test`
4. Update docs/types as needed.
5. Open a PR to `main` with a concise description, motivation, and screenshots/logs if relevant.
6. Be responsive to review feedback; small, incremental PRs are preferred.

## Issue Reporting

- Use GitHub Issues: [github.com/kapersoft/cron-job-api/issues](https://github.com/kapersoft/cron-job-api/issues)
- Include:
  - Expected vs actual behavior
  - Steps to reproduce (minimal code sample if possible)
  - Version info (PHP, OS, library version)
  - Any logs or stack traces

Feature requests are welcome. Explain the use case and why it belongs in this library.

## Security Vulnerabilities

Do not open public issues for security problems.

- Report privately via GitHub Security Advisories: [new advisory](https://github.com/kapersoft/cron-job-api/security/advisories/new)
- Or email the maintainer: <kapersoft@gmail.com>

You will receive an acknowledgment within a reasonable timeframe. We coordinate disclosure and release a fix before publicizing.
