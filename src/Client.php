<?php

declare(strict_types=1);

namespace Kapersoft\CronJobApi;

use GuzzleHttp\Client as GuzzleHttpClient;

final class Client
{
    private const string BASE_URL = 'https://api.cron-job.org';

    public function __construct(
        private readonly string $apiKey,
        private null|string $baseUrl = null,
        private null|GuzzleHttpClient $guzzleHttpClient = null,
    ) {
        $this->baseUrl ??= self::BASE_URL;
        $this->guzzleHttpClient ??= new GuzzleHttpClient;
    }

    public function list(): JobList
    {
        $data = $this->request('GET', '/jobs');

        return JobList::fromArray($data);
    }

    public function get(int $id): DetailedJob
    {
        $data = $this->request('GET', '/jobs/'.$id);

        return DetailedJob::fromArray($data['jobDetails']);
    }

    public function create(Job $job): int
    {
        $job = array_filter($job->toArray(), fn ($value): bool => $value !== null);

        return $this->request('PUT', '/jobs', ['job' => $job]);
    }

    public function update(int $id, Job $job): void
    {
        $job = array_filter($job->toArray(), fn ($value): bool => $value !== null);

        $this->request('PATCH', '/jobs/'.$id, ['job' => $job]);
    }

    public function delete(int $id): void
    {
        $this->request('DELETE', '/jobs/'.$id);
    }

    public function history(int $id): History
    {
        $data = $this->request('GET', '/jobs/'.$id.'/history');

        return History::fromArray($data);
    }

    public function historyItem(int $id, string $identifier): HistoryDetails
    {
        $data = $this->request('GET', '/jobs/'.$id.'/history/'.$identifier);

        return HistoryDetails::fromArray($data['jobHistoryDetails']);
    }

    private function request(string $method, string $url, array $body = []): array|string|int
    {
        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$this->apiKey,
            ],
            'http_errors' => true,
        ];

        if ($body !== [] && $method !== 'GET') {
            $options['json'] = $body;
        }

        $response = $this->guzzleHttpClient->request(
            method: $method,
            uri: mb_rtrim((string) $this->baseUrl, '/').$url,
            options: $options,
        );

        $responseBody = (string) $response->getBody();

        return json_validate($responseBody) ? json_decode($responseBody, true) : $responseBody;
    }
}
