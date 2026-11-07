<?php

declare(strict_types=1);

namespace App\Modules\Support;

final class SupportModule
{
    public const KEY = 'support';

    public static function key(): string
    {
        return self::KEY;
    }
}
