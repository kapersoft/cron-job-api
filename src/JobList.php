<?php

declare(strict_types=1);

namespace Kapersoft\CronJobApi;

final class JobList
{
    public function __construct(
        /**
         * The list of jobs
         *
         * @property array<JobDetails>
         */
        public array $jobs,

        /**
         * Whether some of the jobs have failed
         */
        public bool $someFailed,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            jobs: array_map(fn (array $job): Job => Job::fromArray($job), $data['jobs']),
            someFailed: $data['someFailed'],
        );
    }

    public function toArray(): array
    {
        return [
            'jobs' => $this->jobs,
            'someFailed' => $this->someFailed,
        ];
    }
}
