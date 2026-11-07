<?php

declare(strict_types=1);

namespace App\Modules\Core;

final class CoreModule
{
    public const KEY = 'core';

    public static function key(): string
    {
        return self::KEY;
    }
}
