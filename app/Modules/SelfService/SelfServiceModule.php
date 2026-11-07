<?php

declare(strict_types=1);

namespace App\Modules\SelfService;

final class SelfServiceModule
{
    public const KEY = 'self_service';

    public static function key(): string
    {
        return self::KEY;
    }
}
