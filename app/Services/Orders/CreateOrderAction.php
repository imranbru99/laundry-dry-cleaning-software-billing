<?php

declare(strict_types=1);

namespace App\Services\Orders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Tenant;
use App\Services\Money;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class CreateOrderAction
{
    /**
     * @param  array{branch_id:int,customer_id?:int,type?:string,items:list<array{name:string,quantity:int,unit_price:int,catalog_item_id?:int,catalog_service_id?:int}>}  $payload
     */
    public function handle(Tenant $tenant, array $payload, ?int $userId = null): Order
    {
        return DB::transaction(function () use ($tenant, $payload, $userId): Order {
            $subtotal = 0;
            foreach ($payload['items'] as $item) {
                $subtotal += ((int) $item['quantity']) * ((int) $item['unit_price']);
            }

            $taxMode = ($tenant->tax_mode ?? 'exclusive') === 'inclusive';
            $taxRate = (int) (($tenant->settings['tax_rate_bps'] ?? 0));
            $money = Money::applyTax($subtotal, $taxRate, $taxMode);

            $order = Order::query()->create([
                'tenant_id' => $tenant->id,
                'branch_id' => $payload['branch_id'],
                'customer_id' => $payload['customer_id'] ?? null,
                'number' => 'ORD-'.strtoupper(Str::random(8)),
                'type' => $payload['type'] ?? 'counter',
                'status' => 'received',
                'subtotal' => $money['subtotal'],
                'tax_total' => $money['tax'],
                'discount_total' => 0,
                'delivery_fee' => (int) ($payload['delivery_fee'] ?? 0),
                'grand_total' => $money['total'] + (int) ($payload['delivery_fee'] ?? 0),
                'amount_paid' => 0,
                'currency' => $tenant->currency,
                'created_by' => $userId,
                'special_instructions' => $payload['special_instructions'] ?? null,
            ]);

            foreach ($payload['items'] as $item) {
                OrderItem::query()->create([
                    'tenant_id' => $tenant->id,
                    'order_id' => $order->id,
                    'catalog_item_id' => $item['catalog_item_id'] ?? null,
                    'catalog_service_id' => $item['catalog_service_id'] ?? null,
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $item['quantity'] * $item['unit_price'],
                    'addons' => $item['addons'] ?? null,
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            return $order->load('items');
        });
    }
}