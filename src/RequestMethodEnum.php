<?php

declare(strict_types=1);

namespace Kapersoft\CronJobApi;

enum RequestMethodEnum: int
{
    case GET = 0;
    case POST = 1;
    case OPTIONS = 2;
    case HEAD = 3;
    case PUT = 4;
    case DELETE = 5;
    case TRACE = 6;
    case CONNECT = 7;
    case PATCH = 8;
}
