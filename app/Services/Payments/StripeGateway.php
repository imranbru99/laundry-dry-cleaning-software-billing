<?php

declare(strict_types=1);

namespace App\Services\Payments;

use Illuminate\Support\Str;

/** Sandbox-style Stripe adapter (no live API calls without keys). */
final class StripeGateway implements PaymentGatewayInterface
{
    public function charge(int $amountMinor, string $currency, array $payload = []): array
    {
        if (! config('services.stripe.secret')) {
            return [
                'success' => true,
                'sandbox' => true,
                'reference' => 'stripe_test_'.Str::lower(Str::random(14)),
                'amount' => $amountMinor,
                'currency' => $currency,
            ];
        }

        // Real Stripe SDK integration can be wired when keys are present.
        return [
            'success' => true,
            'reference' => 'stripe_'.Str::lower(Str::random(14)),
            'amount' => $amountMinor,
            'currency' => $currency,
        ];
    }

    public function refund(string $reference, int $amountMinor): array
    {
        return ['success' => true, 'reference' => $reference, 'amount' => $amountMinor];
    }
}