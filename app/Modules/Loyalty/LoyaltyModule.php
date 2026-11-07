<?php

declare(strict_types=1);

namespace App\Modules\Loyalty;

final class LoyaltyModule
{
    public const KEY = 'loyalty';

    public static function key(): string
    {
        return self::KEY;
    }
}
