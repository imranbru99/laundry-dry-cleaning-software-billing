<?php

declare(strict_types=1);

namespace App\Services;

final class Money
{
    public static function ofMajor(float|string $amount): int
    {
        return (int) round(((float) $amount) * 100);
    }

    public static function toMajor(int $minor): string
    {
        return number_format($minor / 100, 2, '.', '');
    }

    public static function percentOf(int $minor, int $percentBps): int
    {
        return (int) round($minor * $percentBps / 10000);
    }

    public static function applyTax(int $subtotal, int $taxRateBps, bool $inclusive): array
    {
        if ($inclusive) {
            $tax = (int) round($subtotal - ($subtotal * 10000 / (10000 + $taxRateBps)));

            return ['subtotal' => $subtotal - $tax, 'tax' => $tax, 'total' => $subtotal];
        }

        $tax = self::percentOf($subtotal, $taxRateBps);

        return ['subtotal' => $subtotal, 'tax' => $tax, 'total' => $subtotal + $tax];
    }
}