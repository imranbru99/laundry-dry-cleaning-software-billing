<?php

declare(strict_types=1);

namespace App\Modules\Franchise;

final class FranchiseModule
{
    public const KEY = 'franchise';

    public static function key(): string
    {
        return self::KEY;
    }
}
