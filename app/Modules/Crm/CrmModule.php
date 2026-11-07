<?php

declare(strict_types=1);

namespace App\Modules\Crm;

final class CrmModule
{
    public const KEY = 'crm';

    public static function key(): string
    {
        return self::KEY;
    }
}
