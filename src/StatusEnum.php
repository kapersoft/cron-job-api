<?php

declare(strict_types=1);

namespace Kapersoft\CronJobApi;

enum StatusEnum: int
{
    case UNKNOWN = 0;
    case OK = 1;
    case FAILED_DNS = 2;
    case FAILED_CONNECTION = 3;
    case FAILED_HTTP = 4;
    case FAILED_TIMEOUT = 5;
    case FAILED_TOO_MUCH_DATA = 6;
    case FAILED_INVALID_URL = 7;
    case FAILED_INTERNAL = 8;
    case FAILED_UNKNOWN = 9;

}
