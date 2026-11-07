<?php

declare(strict_types=1);

namespace App\Modules\Catalog;

final class CatalogModule
{
    public const KEY = 'catalog';

    public static function key(): string
    {
        return self::KEY;
    }
}
