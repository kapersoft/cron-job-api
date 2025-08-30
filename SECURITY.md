# Security Policy

## Supported Versions

- **Library**: Only the latest released version is supported with security fixes.
- **PHP**: Matches `composer.json` constraint (currently **^8.4**). Older PHP versions are not supported.
- **Dependencies**: We follow upstream security releases; keep your lockfile up to date.

## Reporting a Vulnerability

- **Do not** open public issues for vulnerabilities.
- Prefer GitHub Security Advisories: open a private report via [Report a vulnerability](https://github.com/kapersoft/cron-job-api/security/advisories/new).
- Alternatively, email: [kapersoft@gmail.com](mailto:kapersoft@gmail.com).

Please include:

- A clear description, affected versions, and reproduction steps or PoC.
- Impact assessment and suggested remediation if known.

Response & handling:

- We aim to acknowledge within 48 hours and provide a mitigation or timeline within 7 days.
- Coordinated disclosure is appreciated; we’ll credit reporters unless you request otherwise.

## Security Considerations when using the package

- **API Key handling**: The client uses a Bearer token in the `Authorization` header. Store keys in environment secrets; never hardcode or commit them. Rotate regularly.
- **Logging**: Avoid logging request headers or full requests/responses. If logging, redact `Authorization` and any sensitive payload fields.
- **Transport security**: Endpoints are HTTPS. Do not disable TLS verification. Use system CAs or an up-to-date trust store.
- **Timeouts/retries**: The default `GuzzleHttp\Client` has no request timeout configured here. Provide your own Guzzle client with strict timeouts and, if needed, retry/backoff policies when constructing `Kapersoft\CronJobApi\Client`.
- **Least privilege**: Generate and use credentials with minimal permissions for the tasks you automate (rotate if sharing contexts).
- **Input validation**: Validate any user-supplied values before passing them into API calls to prevent injection into downstream systems.
- **Dependency hygiene**: Keep `composer.lock` current and run security updates regularly.

## Questions?

- General questions: open a normal issue at the repo’s [Issues](https://github.com/kapersoft/cron-job-api/issues) page.
- Security-specific questions: email [kapersoft@gmail.com](mailto:kapersoft@gmail.com) or use the private advisory channel.
