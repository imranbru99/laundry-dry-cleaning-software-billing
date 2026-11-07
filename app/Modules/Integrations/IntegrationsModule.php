<?php

declare(strict_types=1);

namespace App\Modules\Integrations;

final class IntegrationsModule
{
    public const KEY = 'integrations';

    public static function key(): string
    {
        return self::KEY;
    }
}
