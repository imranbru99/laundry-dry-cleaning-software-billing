<?php

declare(strict_types=1);

namespace App\Services\Payments;

use Illuminate\Support\Str;

/** Local-market sandbox gateway (bKash). */
final class BkashGateway implements PaymentGatewayInterface
{
    public function charge(int $amountMinor, string $currency, array $payload = []): array
    {
        return [
            'success' => true,
            'sandbox' => true,
            'reference' => 'BKASH-'.strtoupper(Str::random(12)),
            'amount' => $amountMinor,
            'currency' => $currency ?: 'BDT',
        ];
    }

    public function refund(string $reference, int $amountMinor): array
    {
        return ['success' => true, 'reference' => $reference, 'amount' => $amountMinor];
    }
}