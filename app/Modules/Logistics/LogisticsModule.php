<?php

declare(strict_types=1);

namespace App\Modules\Logistics;

final class LogisticsModule
{
    public const KEY = 'logistics';

    public static function key(): string
    {
        return self::KEY;
    }
}
