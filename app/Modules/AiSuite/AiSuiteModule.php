<?php

declare(strict_types=1);

namespace App\Modules\AiSuite;

final class AiSuiteModule
{
    public const KEY = 'ai_suite';

    public static function key(): string
    {
        return self::KEY;
    }
}
