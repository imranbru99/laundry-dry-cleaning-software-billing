<?php

declare(strict_types=1);

namespace App\Modules\Communication;

final class CommunicationModule
{
    public const KEY = 'communication';

    public static function key(): string
    {
        return self::KEY;
    }
}
