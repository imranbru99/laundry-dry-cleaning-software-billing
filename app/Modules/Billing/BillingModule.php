<?php

declare(strict_types=1);

namespace App\Modules\Billing;

final class BillingModule
{
    public const KEY = 'billing';

    public static function key(): string
    {
        return self::KEY;
    }
}
