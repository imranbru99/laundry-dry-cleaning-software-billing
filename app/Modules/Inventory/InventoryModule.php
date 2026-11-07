<?php

declare(strict_types=1);

namespace App\Modules\Inventory;

final class InventoryModule
{
    public const KEY = 'inventory';

    public static function key(): string
    {
        return self::KEY;
    }
}
