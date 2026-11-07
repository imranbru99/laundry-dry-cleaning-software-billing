<?php

declare(strict_types=1);

namespace App\Modules\Production;

final class ProductionModule
{
    public const KEY = 'production';

    public static function key(): string
    {
        return self::KEY;
    }
}
