<?php

declare(strict_types=1);

namespace App\Services\Billing;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

final class RecordPaymentAction
{
    public function handle(Order|Invoice $payable, string $method, int $amount, ?int $userId = null, array $meta = []): Payment
    {
        return DB::transaction(function () use ($payable, $method, $amount, $userId, $meta): Payment {
            $payment = Payment::query()->create([
                'tenant_id' => $payable->tenant_id,
                'order_id' => $payable instanceof Order ? $payable->id : $payable->order_id,
                'invoice_id' => $payable instanceof Invoice ? $payable->id : null,
                'customer_id' => $payable->customer_id,
                'method' => $method,
                'status' => 'completed',
                'amount' => $amount,
                'currency' => $payable->currency,
                'meta' => $meta,
                'received_by' => $userId,
            ]);

            $payable->amount_paid = (int) $payable->amount_paid + $amount;
            if ($payable->amount_paid >= $payable->grand_total) {
                if ($payable instanceof Order) {
                    // keep order status separate; mark paid via meta
                    $metaStatus = $payable->meta ?? [];
                    $metaStatus['payment_status'] = 'paid';
                    $payable->meta = $metaStatus;
                } else {
                    $payable->status = 'paid';
                }
            }
            $payable->save();

            return $payment;
        });
    }
}