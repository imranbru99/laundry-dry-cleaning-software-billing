<?php

declare(strict_types=1);

namespace App\Services\Payments;

use Illuminate\Support\Str;

final class CashGateway implements PaymentGatewayInterface
{
    public function charge(int $amountMinor, string $currency, array $payload = []): array
    {
        return [
            'success' => true,
            'reference' => 'CASH-'.strtoupper(Str::random(10)),
            'amount' => $amountMinor,
            'currency' => $currency,
        ];
    }

    public function refund(string $reference, int $amountMinor): array
    {
        return ['success' => true, 'reference' => $reference, 'amount' => $amountMinor];
    }
}