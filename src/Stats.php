<?php

declare(strict_types=1);

namespace Kapersoft\CronJobApi;

final class Stats
{
    public function __construct(
        /**
         * Time from transfer start until name lookups completed (in microseconds)
         */
        public int $nameLookup,

        /**
         * Time from transfer start until socket connect completed (in microseconds)
         */
        public int $connect,

        /**
         * Time from transfer start until SSL handshake completed (in microseconds) - 0 if not using SSL
         */
        public int $appConnect,

        /**
         * Time from transfer start until beginning of data transfer (in microseconds)
         */
        public int $preTransfer,

        /**
         * Time from transfer start until the first response byte is received (in microseconds)
         */
        public int $startTransfer,

        /**
         * Total transfer time (in microseconds)
         */
        public int $total,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nameLookup: $data['nameLookup'],
            connect: $data['connect'],
            appConnect: $data['appConnect'],
            preTransfer: $data['preTransfer'],
            startTransfer: $data['startTransfer'],
            total: $data['total'],
        );
    }

    public function toArray(): array
    {
        return [
            'nameLookup' => $this->nameLookup,
            'connect' => $this->connect,
            'appConnect' => $this->appConnect,
            'preTransfer' => $this->preTransfer,
            'startTransfer' => $this->startTransfer,
            'total' => $this->total,
        ];
    }
}
