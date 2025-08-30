<?php

declare(strict_types=1);

namespace Kapersoft\CronJobApi\Tests;

use Faker\Factory;
use Faker\Generator;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Kapersoft\CronJobApi\Client;
use Kapersoft\CronJobApi\HistoryDetails;
use Kapersoft\CronJobApi\Job;
use Kapersoft\CronJobApi\RequestMethodEnum;
use Kapersoft\CronJobApi\StatusEnum;
use Kapersoft\CronJobApi\TypeEnum;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    private array $container = [];

    private Generator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = Factory::create();
    }

    public function test_it_can_list_jobs(): void
    {
        // Arrange
        $job1 = $this->fakeJob();
        $job2 = $this->fakeJob();
        $job3 = $this->fakeJob();
        $client = $this->mockGuzzleClient([
            'jobs' => [
                $job1,
                $job2,
                $job3,
            ],
            'someFailed' => false,
        ]);

        // Act
        $jobList = $client->list();

        // Assert
        $this->assertRequestSent('GET', 'https://api.cron-job.org/jobs');
        $this->assertCount(3, $jobList->jobs);
        $this->assertInstanceOf(Job::class, $jobList->jobs[0]);
        $this->assertSame($job1, $jobList->jobs[0]->toArray());
        $this->assertInstanceOf(Job::class, $jobList->jobs[1]);
        $this->assertSame($job2, $jobList->jobs[1]->toArray());
        $this->assertInstanceOf(Job::class, $jobList->jobs[2]);
        $this->assertSame($job3, $jobList->jobs[2]->toArray());
        $this->assertFalse($jobList->someFailed);
    }

    public function test_it_can_get_a_job(): void
    {
        // Arrange
        $detailedJob = $this->fakeDetailedJob();
        $client = $this->mockGuzzleClient(['jobDetails' => $detailedJob]);

        // Act
        $output = $client->get($detailedJob['jobId']);

        // Assert
        $this->assertRequestSent('GET', 'https://api.cron-job.org/jobs/'.$detailedJob['jobId']);
        $this->assertSame($detailedJob, $output->toArray());
    }

    public function test_it_can_create_a_job(): void
    {
        // Arrange
        $job = Job::fromArray($this->fakeJob());
        $client = $this->mockGuzzleClient('42');

        // Act
        $output = $client->create($job);

        // Assert
        $this->assertRequestSent('PUT', 'https://api.cron-job.org/jobs', ['job' => $job->toArray()]);
        $this->assertSame(42, $output);
    }

    public function test_it_can_update_a_job(): void
    {
        // Arrange
        $job = new Job(title: 'My new Job');
        $client = $this->mockGuzzleClient('{}');

        // Act
        $client->update(42, $job);

        // Assert
        $this->assertRequestSent('PATCH', 'https://api.cron-job.org/jobs/42', ['job' => ['title' => 'My new Job']]);
    }

    public function test_it_can_delete_a_job(): void
    {
        // Arrange
        $client = $this->mockGuzzleClient();

        // Act
        $client->delete(42);

        // Assert
        $this->assertRequestSent('DELETE', 'https://api.cron-job.org/jobs/42');
    }

    public function test_it_can_list_history(): void
    {
        // Arrange
        $historyItem1 = $this->fakeHistoryItem();
        $historyItem2 = $this->fakeHistoryItem();
        $historyItem3 = $this->fakeHistoryItem();
        $client = $this->mockGuzzleClient([
            'history' => [
                $historyItem1,
                $historyItem2,
                $historyItem3,
            ],
            'predictions' => [1719859200, 1719902400, 1719945600],
        ]);

        // Act
        $jobHistory = $client->history(42);

        // Assert
        $this->assertRequestSent('GET', 'https://api.cron-job.org/jobs/42/history');
        $this->assertInstanceOf(HistoryDetails::class, $jobHistory->history[0]);
        $this->assertSame($historyItem1, $jobHistory->history[0]->toArray());
        $this->assertInstanceOf(HistoryDetails::class, $jobHistory->history[1]);
        $this->assertSame($historyItem2, $jobHistory->history[1]->toArray());
        $this->assertInstanceOf(HistoryDetails::class, $jobHistory->history[2]);
        $this->assertSame($historyItem3, $jobHistory->history[2]->toArray());
        $this->assertCount(3, $jobHistory->predictions);
        $this->assertSame([1719859200, 1719902400, 1719945600], $jobHistory->predictions);
    }

    public function test_it_can_get_a_history_item(): void
    {
        // Arrange
        $historyItem = $this->fakeHistoryItem();
        $client = $this->mockGuzzleClient(['jobHistoryDetails' => $historyItem]);

        // Act
        $jobHistoryDetails = $client->historyItem(42, $historyItem['identifier']);

        // Assert
        $this->assertRequestSent('GET', 'https://api.cron-job.org/jobs/42/history/'.$historyItem['identifier']);
        $this->assertSame($historyItem, $jobHistoryDetails->toArray());
    }

    private function fakeJob(): array
    {
        return [
            'jobId' => $this->generator->randomNumber(),
            'enabled' => $this->generator->boolean(),
            'title' => $this->generator->name(),
            'saveResponses' => $this->generator->boolean(),
            'url' => $this->generator->url(),
            'lastStatus' => $this->generator->randomElement(StatusEnum::cases())->value,
            'lastDuration' => $this->generator->randomNumber(),
            'lastExecution' => $this->generator->unixTime(),
            'nextExecution' => $this->generator->unixTime(),
            'type' => $this->generator->randomElement(TypeEnum::cases())->value,
            'requestTimeout' => $this->generator->randomNumber(),
            'redirectSuccess' => $this->generator->boolean(),
            'folderId' => $this->generator->randomNumber(),
            'schedule' => [
                'timezone' => $this->generator->timezone(),
                'expiresAt' => $this->generator->unixTime(),
                'hours' => [$this->generator->randomNumber()],
                'mdays' => [$this->generator->randomNumber()],
                'minutes' => [$this->generator->randomNumber()],
                'months' => [$this->generator->randomNumber()],
                'wdays' => [$this->generator->randomNumber()],
            ],
            'requestMethod' => $this->generator->randomElement(RequestMethodEnum::cases())->value,
        ];
    }

    private function fakeDetailedJob(): array
    {
        $this->generator = Factory::create();

        return [
            ...$this->fakeJob(),
            'auth' => [
                'enable' => $this->generator->boolean(),
                'user' => $this->generator->name(),
                'password' => $this->generator->password(),
            ],
            'notification' => [
                'onFailure' => $this->generator->boolean(),
                'onSuccess' => $this->generator->boolean(),
                'onDisable' => $this->generator->boolean(),
            ],
            'extendedData' => [
                'headers' => [$this->generator->word() => $this->generator->sentence()],
                'body' => $this->generator->sentence(),
            ],
        ];
    }

    private function fakeHistoryItem(): array
    {
        $this->generator = Factory::create();

        return [
            'jobId' => $this->generator->randomNumber(),
            'identifier' => $this->generator->uuid(),
            'date' => $this->generator->unixTime(),
            'datePlanned' => $this->generator->unixTime(),
            'jitter' => $this->generator->randomNumber(),
            'url' => $this->generator->url(),
            'duration' => $this->generator->randomNumber(),
            'status' => $this->generator->randomElement(StatusEnum::cases())->value,
            'statusText' => $this->generator->sentence(),
            'httpStatus' => $this->generator->randomNumber(),
            'headers' => $this->generator->sentence(),
            'body' => $this->generator->sentence(),
            'stats' => [
                'nameLookup' => $this->generator->randomNumber(),
                'connect' => $this->generator->randomNumber(),
                'appConnect' => $this->generator->randomNumber(),
                'preTransfer' => $this->generator->randomNumber(),
                'startTransfer' => $this->generator->randomNumber(),
                'total' => $this->generator->randomNumber(),
            ],
        ];
    }

    private function mockGuzzleClient(string|array $responseBody = '', int $responseStatus = 200): Client
    {
        // Fake the body
        $mockHandler = new MockHandler([
            new Response(
                status: $responseStatus,
                headers: [],
                body: is_array($responseBody) ? json_encode($responseBody) : $responseBody,
            ),
        ]);

        $handlerStack = HandlerStack::create($mockHandler);

        // Record the requests
        $history = Middleware::history($this->container);
        $handlerStack->push($history);

        $guzzleHttpClient = new GuzzleHttpClient(['handler' => $handlerStack]);

        return new Client('secret', null, $guzzleHttpClient);
    }

    private function assertRequestSent(string $method, string $url, string|array|null $body = null): void
    {
        $this->assertCount(1, $this->container);

        /** @var Request $request */
        $request = $this->container[0]['request'];
        $this->assertSame($method, $request->getMethod());
        $this->assertSame($url, (string) $request->getUri());

        match (true) {
            $body === null => $this->assertEmpty($request->getBody()->getContents()),
            is_array($body) => $this->assertEquals($body, json_decode($request->getBody()->getContents(), true)),
            is_string($body) => $this->assertSame($body, $request->getBody()->getContents()),
        };

        $headers = $request->getHeaders();
        $this->assertArrayHasKey('Content-Type', $request->getHeaders());
        $this->assertSame('application/json', $headers['Content-Type'][0]);
        $this->assertArrayHasKey('Authorization', $headers);
        $this->assertSame('Bearer secret', $headers['Authorization'][0]);
    }
}
