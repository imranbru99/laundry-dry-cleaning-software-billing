<?php

declare(strict_types=1);

namespace App\Modules\Orders;

final class OrdersModule
{
    public const KEY = 'orders';

    public static function key(): string
    {
        return self::KEY;
    }
}
