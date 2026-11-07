<?php

declare(strict_types=1);

namespace App\Modules\Tagging;

final class TaggingModule
{
    public const KEY = 'tagging';

    public static function key(): string
    {
        return self::KEY;
    }
}
