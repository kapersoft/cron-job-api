<?php

declare(strict_types=1);

namespace Kapersoft\CronJobApi;

final class Schedule
{
    public function __construct(
        /**
         * Schedule time zone (see here for a list of supported values)
         */
        public string $timezone,

        /**
         * Date/time (in job's time zone) after which the job expires, i.e. after which it is not scheduled anymore (format: YYYYMMDDhhmmss, 0 = does not expire)
         */
        public int $expiresAt,

        /**
         * Hours in which to execute the job (0-23; [-1] = every hour)
         */
        public array $hours,

        /**
         * Days of month in which to execute the job (1-31; [-1] = every day of month)
         */
        public array $mdays,

        /**
         * Minutes in which to execute the job (0-59; [-1] = every minute)
         */
        public array $minutes,

        /**
         * Months in which to execute the job (1-12; [-1] = every month)
         */
        public array $months,

        /**
         * Days of week in which to execute the job (0=Sunday - 6=Saturday; [-1] = every day of week)
         */
        public array $wdays,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            timezone: $data['timezone'],
            expiresAt: $data['expiresAt'],
            hours: $data['hours'],
            mdays: $data['mdays'],
            minutes: $data['minutes'],
            months: $data['months'],
            wdays: $data['wdays'],
        );
    }

    public function toArray(): array
    {
        return [
            'timezone' => $this->timezone,
            'expiresAt' => $this->expiresAt,
            'hours' => $this->hours,
            'mdays' => $this->mdays,
            'minutes' => $this->minutes,
            'months' => $this->months,
            'wdays' => $this->wdays,
        ];
    }
}
