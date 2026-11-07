<?php

declare(strict_types=1);

namespace App\Services\Payments;

interface PaymentGatewayInterface
{
    public function charge(int $amountMinor, string $currency, array $payload = []): array;

    public function refund(string $reference, int $amountMinor): array;
}