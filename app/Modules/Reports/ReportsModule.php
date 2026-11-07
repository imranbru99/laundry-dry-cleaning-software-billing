<?php

declare(strict_types=1);

namespace App\Modules\Reports;

final class ReportsModule
{
    public const KEY = 'reports';

    public static function key(): string
    {
        return self::KEY;
    }
}
