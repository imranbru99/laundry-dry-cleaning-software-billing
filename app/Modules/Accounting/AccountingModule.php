<?php

declare(strict_types=1);

namespace App\Modules\Accounting;

final class AccountingModule
{
    public const KEY = 'accounting';

    public static function key(): string
    {
        return self::KEY;
    }
}
