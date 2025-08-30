<?php

declare(strict_types=1);

namespace Kapersoft\CronJobApi;

final class HistoryDetails
{
    public function __construct(
        /**
         * Identifier of the associated cron job
         */
        public int $jobId,

        /**
         * Identifier of the history item
         */
        public string $identifier,

        /**
         * Unix timestamp (in seconds) of the actual execution
         */
        public int $date,

        /**
         * Unix timestamp (in seconds) of the planned/ideal execution
         */
        public int $datePlanned,

        /**
         * Scheduling jitter in milliseconds
         */
        public int $jitter,

        /**
         * Job URL at time of execution
         */
        public string $url,

        /**
         * Actual job duration in milliseconds
         */
        public int $duration,

        /**
         * Status of execution
         */
        public StatusEnum $status,

        /**
         * Detailed job status Description
         */
        public string $statusText,

        /**
         * HTTP status code returned by the host, if any
         */
        public int $httpStatus,

        /**
         * Raw response headers returned by the host (null if unavailable)
         */
        public null|string $headers,

        /**
         * Raw response body returned by the host (null if unavailable)
         */
        public null|string $body,

        /**
         * Additional timing information for this request
         */
        public Stats $stats,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            jobId: $data['jobId'],
            identifier: $data['identifier'],
            date: $data['date'],
            datePlanned: $data['datePlanned'],
            jitter: $data['jitter'],
            url: $data['url'],
            duration: $data['duration'],
            status: StatusEnum::from($data['status']),
            statusText: $data['statusText'],
            httpStatus: $data['httpStatus'],
            headers: $data['headers'],
            body: $data['body'],
            stats: Stats::fromArray($data['stats']),
        );
    }

    public function toArray(): array
    {
        return [
            'jobId' => $this->jobId,
            'identifier' => $this->identifier,
            'date' => $this->date,
            'datePlanned' => $this->datePlanned,
            'jitter' => $this->jitter,
            'url' => $this->url,
            'duration' => $this->duration,
            'status' => $this->status->value,
            'statusText' => $this->statusText,
            'httpStatus' => $this->httpStatus,
            'headers' => $this->headers,
            'body' => $this->body,
            'stats' => $this->stats->toArray(),
        ];
    }
}
