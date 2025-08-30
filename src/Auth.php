<?php

declare(strict_types=1);

namespace Kapersoft\CronJobApi;

final class Auth
{
    public function __construct(
        /**
         * Whether to enable HTTP basic authentication or not.
         */
        public bool $enable,

        /**
         * HTTP basic auth username
         */
        public string $user,

        /**
         * HTTP basic auth password
         */
        public string $password,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            enable: $data['enable'],
            user: $data['user'],
            password: $data['password'],
        );
    }

    public function toArray(): array
    {
        return [
            'enable' => $this->enable,
            'user' => $this->user,
            'password' => $this->password,
        ];
    }
}
