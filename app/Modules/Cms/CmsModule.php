<?php

declare(strict_types=1);

namespace App\Modules\Cms;

final class CmsModule
{
    public const KEY = 'cms';

    public static function key(): string
    {
        return self::KEY;
    }
}
