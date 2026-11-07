<?php

declare(strict_types=1);

namespace App\Modules\B2b;

final class B2bModule
{
    public const KEY = 'b2b';

    public static function key(): string
    {
        return self::KEY;
    }
}
