<?php

declare(strict_types=1);

namespace App\Modules\Subscriptions;

final class SubscriptionsModule
{
    public const KEY = 'subscriptions';

    public static function key(): string
    {
        return self::KEY;
    }
}
