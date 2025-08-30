<?php

declare(strict_types=1);

namespace Kapersoft\CronJobApi;

final class DetailedJob
{
    public function __construct(
        /**
         * Job identifier (read only; ignored during job creation or update)
         */
        public int $jobId,

        /**
         * Whether the job is enabled (i.e. being executed) or not
         */
        public bool $enabled,

        /**
         * Job title
         */
        public string $title,

        /**
         * Whether to save job response header/body or not
         */
        public bool $saveResponses,

        /**
         * Job URL
         */
        public string $url,

        /**
         * Last execution status (read only)
         */
        public StatusEnum $lastStatus,

        /**
         * Last execution duration in milliseconds (read only)
         */
        public int $lastDuration,

        /**
         * Unix timestamp of last execution (in seconds; read only)
         */
        public int $lastExecution,

        /**
         * Unix timestamp of predicted next execution (in seconds), null if no prediction available (read only)
         */
        public null|int $nextExecution,

        /**
         * Job type (read only)
         */
        public TypeEnum $type,

        /**
         * Job timeout in seconds
         */
        public int $requestTimeout,

        /**
         * Whether to treat 3xx HTTP redirect status codes as success or not
         */
        public bool $redirectSuccess,

        /**
         * The identifier of the folder this job resides in
         */
        public int $folderId,

        /**
         * Job schedule
         */
        public Schedule $schedule,

        /**
         * HTTP request method
         */
        public RequestMethodEnum $requestMethod,

        /**
         * HTTP authentication settings
         */
        public Auth $auth,

        /**
         * Notification settings
         */
        public NotificationSettings $notification,

        /**
         * Extended request data
         */
        public ExtendedData $extendedData,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            jobId: $data['jobId'],
            enabled: $data['enabled'],
            title: $data['title'],
            saveResponses: $data['saveResponses'],
            url: $data['url'],
            lastStatus: StatusEnum::from($data['lastStatus']),
            lastDuration: $data['lastDuration'],
            lastExecution: $data['lastExecution'],
            nextExecution: $data['nextExecution'],
            type: TypeEnum::from($data['type']),
            requestTimeout: $data['requestTimeout'],
            redirectSuccess: $data['redirectSuccess'],
            folderId: $data['folderId'],
            schedule: Schedule::fromArray($data['schedule']),
            requestMethod: RequestMethodEnum::from($data['requestMethod']),
            auth: Auth::fromArray($data['auth']),
            notification: NotificationSettings::fromArray($data['notification']),
            extendedData: ExtendedData::fromArray($data['extendedData']),
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
            'lastStatus' => $this->lastStatus->value,
            'lastDuration' => $this->lastDuration,
            'lastExecution' => $this->lastExecution,
            'nextExecution' => $this->nextExecution,
            'type' => $this->type->value,
            'requestTimeout' => $this->requestTimeout,
            'redirectSuccess' => $this->redirectSuccess,
            'folderId' => $this->folderId,
            'schedule' => $this->schedule->toArray(),
            'requestMethod' => $this->requestMethod->value,
            'auth' => $this->auth->toArray(),
            'notification' => $this->notification->toArray(),
            'extendedData' => $this->extendedData->toArray(),
        ];
    }
}
