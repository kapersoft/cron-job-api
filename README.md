# cron-job-api (PHP client for cron-job.org)

Small, typed PHP 8.4+ client for the cron-job.org v1 API. No framework required; uses Guzzle under the hood.

## What is this repo?

This is `kapersoft/cron-job-api`, a lightweight wrapper around the public cron-job.org API, providing simple methods to list, create, update, delete, and inspect jobs and their history.

## What is cron-job.org?

[cron-job.org](https://cron-job.org) is a hosted scheduler. It executes HTTP(S) requests on schedules (minutes to months), great for pinging endpoints, running webhooks, and health checks without maintaining your own cron infrastructure.

## Installation

```bash
composer require kapersoft/cron-job-api
```

Requirements:

- PHP ^8.4
- Guzzle ^7.10 (pulled in automatically)

## How to use

Basic client setup and common operations. Replace `$apiKey` with your cron-job.org API token.

```php
<?php

use Kapersoft\CronJobApi\Client;

$client = new Client($apiKey); // Authorization: Bearer <token>
```

List jobs:

```php
use Kapersoft\CronJobApi\Job;

$jobList = $client->list();
foreach ($jobList->jobs as $job) {
    /** @var Job $job */
    echo $job->jobId.' '.$job->title.PHP_EOL;
}
```

Get one job (with full details):

```php
$detailed = $client->get(123); // returns DetailedJob
echo $detailed->title;
```

Create a job:

```php
use Kapersoft\CronJobApi\{Job, Schedule, RequestMethodEnum};

$newJobId = $client->create(new Job(
    enabled: true,
    title: 'Ping production',
    url: 'https://example.com/health',
    requestTimeout: 30,
    redirectSuccess: true,
    schedule: new Schedule(
        timezone: 'UTC',
        expiresAt: 0,        // 0 = never expires
        hours: [-1],         // every hour
        mdays: [-1],         // every day of month
        minutes: [0, 30],    // on minute 0 and 30
        months: [-1],        // every month
        wdays: [-1],         // every day of week
    ),
    requestMethod: RequestMethodEnum::GET,
));
```

Update a job (partial fields):

```php
$client->update($newJobId, new Job(title: 'Ping prod (renamed)'));
```

Delete a job:

```php
$client->delete($newJobId);
```

List history and fetch a specific run:

```php
$history = $client->history(123);
$firstRun = $history->history[0];
$details = $client->historyItem(123, $firstRun->identifier);
```

Optionally provide a custom Guzzle client (timeouts, retries, proxy, etc.):

```php
$guzzle = new \GuzzleHttp\Client(['timeout' => 10]);
$client = new Client($apiKey, baseUrl: null, guzzleHttpClient: $guzzle);
```

## How to report issues

- Open an issue: [GitHub Issues](https://github.com/kapersoft/cron-job-api/issues)
- Please include minimal repro code, expected vs. actual behavior, and versions.

## License information

This library is MIT licensed. See [LICENSE.md](LICENSE.md).

## Security information

Do not post vulnerabilities in public issues. See [SECURITY.md](SECURITY.md).

## How to contribute

Read [CONTRIBUTING.md](CONTRIBUTING.md) for setup, coding standards, and workflow. PRs with focused changes and tests are welcome.
