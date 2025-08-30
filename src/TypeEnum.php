<?php

declare(strict_types=1);

namespace Kapersoft\CronJobApi;

enum TypeEnum: int
{
    case DEFAULT = 0;
    case MONITORING = 1;
}
