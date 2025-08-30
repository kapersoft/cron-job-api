<?php

declare(strict_types=1);

namespace Kapersoft\CronJobApi;

final class Job
{
    public function __construct(
        /**
         * Job identifier (read only; ignored during job creation or update)
         */
        public null|int $jobId = null,

        /**
         * Whether the job is enabled (i.e. being executed) or not
         */
        public null|bool $enabled = null,

        /**
         * Job title
         */
        public null|string $title = null,

        /**
         * Whether to save job response header/body or not
         */
        public null|bool $saveResponses = null,

        /**
         * Job URL
         */
        public null|string $url = null,

        /**
         * Last execution status (read only)
         */
        public null|StatusEnum $lastStatus = null,

        /**
         * Last execution duration in milliseconds (read only)
         */
        public null|int $lastDuration = null,

        /**
         * Unix timestamp of last execution (in seconds; read only)
         */
        public null|int $lastExecution = null,

        /**
         * Unix timestamp of predicted next execution (in seconds), null if no prediction available (read only)
         */
        public null|int $nextExecution = null,

        /**
         * Job type (read only)
         */
        public null|TypeEnum $type = null,

        /**
         * Job timeout in seconds
         */
        public null|int $requestTimeout = null,

        /**
         * Whether to treat 3xx HTTP redirect status codes as success or not
         */
        public null|bool $redirectSuccess = null,

        /**
         * The identifier of the folder this job resides in
         */
        public null|int $folderId = null,

        /**
         * Job schedule
         */
        public null|Schedule $schedule = null,

        /**
         * HTTP request method
         */
        public null|RequestMethodEnum $requestMethod = null,

    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            jobId: $data['jobId'] ?? null,
            enabled: $data['enabled'] ?? null,
            title: $data['title'] ?? null,
            saveResponses: $data['saveResponses'] ?? null,
            url: $data['url'] ?? null,
            lastStatus: StatusEnum::tryFrom($data['lastStatus']) ?? null,
            lastDuration: $data['lastDuration'] ?? null,
            lastExecution: $data['lastExecution'],
            nextExecution: $data['nextExecution'] ?? null,
            type: TypeEnum::tryFrom($data['type']) ?? null,
            requestTimeout: $data['requestTimeout'] ?? null,
            redirectSuccess: $data['redirectSuccess'] ?? null,
            folderId: $data['folderId'] ?? null,
            schedule: isset($data['schedule']) ? Schedule::fromArray($data['schedule']) : null,
            requestMethod: RequestMethodEnum::tryFrom($data['requestMethod']) ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'jobId' => $this->jobId,
            'enabled' => $this->enabled,
            'title' => $this->title,
            'saveResponses' => $this->saveResponses,
            'url' => $this->url,
            'lastStatus' => $this->lastStatus?->value,
            'lastDuration' => $this->lastDuration,
            'lastExecution' => $this->lastExecution,
            'nextExecution' => $this->nextExecution,
            'type' => $this->type?->value,
            'requestTimeout' => $this->requestTimeout,
            'redirectSuccess' => $this->redirectSuccess,
            'folderId' => $this->folderId,
            'schedule' => $this->schedule?->toArray(),
            'requestMethod' => $this->requestMethod?->value,
        ];
    }
}
