<?php

declare(strict_types=1);

namespace Kapersoft\CronJobApi;

final class NotificationSettings
{
    public function __construct(
        /**
         * Whether to send a notification on job failure or not.
         */
        public bool $onFailure,

        /**
         * Whether to send a notification when the job succeeds after a prior failure or not.
         */
        public bool $onSuccess,

        /**
         * Whether to send a notification when the job has been disabled automatically or not.
         */
        public bool $onDisable,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            onFailure: $data['onFailure'],
            onSuccess: $data['onSuccess'],
            onDisable: $data['onDisable'],
        );
    }

    public function toArray(): array
    {
        return [
            'onFailure' => $this->onFailure,
            'onSuccess' => $this->onSuccess,
            'onDisable' => $this->onDisable,
        ];
    }
}
