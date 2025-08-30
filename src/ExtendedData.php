<?php

declare(strict_types=1);

namespace Kapersoft\CronJobApi;

final class ExtendedData
{
    public function __construct(
        /**
         * Request headers (key-value dictionary)
         */
        public array $headers,

        /**
         * Request body data
         */
        public string $body,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            headers: match (true) {
                is_array($data['headers']) => $data['headers'],
                is_string($data['headers']) && json_validate($data['headers']) => json_decode($data['headers'], true),
                default => [],
            },
            body: $data['body'],
        );
    }

    public function toArray(): array
    {
        return [
            'headers' => $this->headers,
            'body' => $this->body,
        ];
    }
}
