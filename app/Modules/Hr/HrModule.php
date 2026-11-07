<?php

declare(strict_types=1);

namespace App\Modules\Hr;

final class HrModule
{
    public const KEY = 'hr';

    public static function key(): string
    {
        return self::KEY;
    }
}
