<?php

declare(strict_types=1);

namespace Kapersoft\CronJobApi;

final class History
{
    public function __construct(
        /**
         * The last execution history items
         *
         * @property array<JobHistoryDetail>
         */
        public array $history,

        /**
         * Unix timestamps (in seconds) of the predicted next executions (up to 3)
         *
         * @property array<int>
         */
        public array $predictions,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            history: array_map(fn (array $history): HistoryDetails => HistoryDetails::fromArray($history), $data['history']),
            predictions: $data['predictions'],
        );
    }

    public function toArray(): array
    {
        return [
            'history' => $this->history,
            'predictions' => $this->predictions,
        ];
    }
}
