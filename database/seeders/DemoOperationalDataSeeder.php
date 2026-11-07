<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Delivery;
use App\Models\Employee;
use App\Models\GarmentTag;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Pickup;
use App\Models\Tenant;
use App\Models\User;
use App\Support\Tenancy\TenantContext;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DemoOperationalDataSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::query()->where('slug', 'sparkle-dry-cleaners')->firstOrFail();
        TenantContext::set($tenant);
        $branchId = $tenant->branches()->where('code', 'GUL')->value('id');
        $driverId = User::query()->where('email', 'driver@demo.test')->value('id');

        $customerCount = (int) env('DEMO_CUSTOMER_COUNT', 300);
        $orderCount = (int) env('DEMO_ORDER_COUNT', 1500);

        if (Customer::query()->where('tenant_id', $tenant->id)->count() < $customerCount) {
            $rows = [];
            for ($i = 1; $i <= $customerCount; $i++) {
                $rows[] = [
                    'tenant_id' => $tenant->id,
                    'type' => $i % 20 === 0 ? 'corporate' : 'individual',
                    'name' => "Customer {$i}",
                    'email' => "customer{$i}@demo.test",
                    'phone' => '+88017'.str_pad((string) $i, 8, '0', STR_PAD_LEFT),
                    'lifetime_value' => random_int(0, 500000),
                    'created_at' => now()->subDays(random_int(0, 365)),
                    'updated_at' => now(),
                ];
                if (count($rows) >= 100) {
                    Customer::query()->insert($rows);
                    $rows = [];
                }
            }
            if ($rows !== []) {
                Customer::query()->insert($rows);
            }
        }

        Customer::query()->updateOrCreate(
            ['tenant_id' => $tenant->id, 'email' => 'customer@demo.test'],
            ['name' => 'Demo Customer', 'type' => 'individual', 'phone' => '+8801711111111']
        );
        Customer::query()->updateOrCreate(
            ['tenant_id' => $tenant->id, 'email' => 'corporate@demo.test'],
            ['name' => 'Grand Hotel BD', 'type' => 'corporate', 'company_name' => 'Grand Hotel BD', 'phone' => '+8801722222222']
        );

        $customerIds = Customer::query()->where('tenant_id', $tenant->id)->pluck('id')->all();
        $statuses = ['received', 'in_production', 'ready', 'out_for_delivery', 'delivered', 'cancelled'];

        if (Order::query()->where('tenant_id', $tenant->id)->count() < $orderCount) {
            for ($i = 1; $i <= $orderCount; $i++) {
                $created = now()->subDays(random_int(0, 365))->subMinutes(random_int(0, 1400));
                $subtotal = random_int(500, 15000);
                $tax = (int) round($subtotal * 0.05);
                $status = $statuses[array_rand($statuses)];
                $orderId = DB::table('orders')->insertGetId([
                    'tenant_id' => $tenant->id,
                    'branch_id' => $branchId,
                    'customer_id' => $customerIds[array_rand($customerIds)],
                    'number' => 'ORD-'.strtoupper(Str::random(8)).$i,
                    'type' => ['counter', 'pickup_delivery', 'b2b'][array_rand(['counter', 'pickup_delivery', 'b2b'])],
                    'status' => $status,
                    'priority' => 'normal',
                    'subtotal' => $subtotal,
                    'tax_total' => $tax,
                    'discount_total' => 0,
                    'delivery_fee' => 0,
                    'grand_total' => $subtotal + $tax,
                    'amount_paid' => in_array($status, ['delivered', 'ready'], true) ? $subtotal + $tax : 0,
                    'currency' => 'BDT',
                    'created_at' => $created,
                    'updated_at' => $created,
                ]);

                $itemId = DB::table('order_items')->insertGetId([
                    'tenant_id' => $tenant->id,
                    'order_id' => $orderId,
                    'name' => ['Shirt', 'Suit', 'Saree'][array_rand(['Shirt', 'Suit', 'Saree'])],
                    'quantity' => random_int(1, 4),
                    'unit_price' => (int) round($subtotal / 2),
                    'line_total' => $subtotal,
                    'created_at' => $created,
                    'updated_at' => $created,
                ]);

                $tagCode = 'TAG-'.strtoupper(Str::random(8)).$i;
                DB::table('garment_tags')->insert([
                    'tenant_id' => $tenant->id,
                    'order_id' => $orderId,
                    'order_item_id' => $itemId,
                    'tag_code' => $tagCode,
                    'barcode' => $tagCode,
                    'qr_payload' => url('/track/'.$tagCode),
                    'status' => $status === 'delivered' ? 'delivered' : 'in_production',
                    'created_at' => $created,
                    'updated_at' => $created,
                ]);

                if ($i % 5 === 0) {
                    $invNumber = 'INV-'.$i;
                    $invoiceId = DB::table('invoices')->insertGetId([
                        'tenant_id' => $tenant->id,
                        'branch_id' => $branchId,
                        'customer_id' => $customerIds[array_rand($customerIds)],
                        'order_id' => $orderId,
                        'number' => $invNumber,
                        'type' => 'invoice',
                        'status' => 'paid',
                        'subtotal' => $subtotal,
                        'tax_total' => $tax,
                        'discount_total' => 0,
                        'grand_total' => $subtotal + $tax,
                        'amount_paid' => $subtotal + $tax,
                        'currency' => 'BDT',
                        'issued_at' => $created,
                        'created_at' => $created,
                        'updated_at' => $created,
                    ]);
                    DB::table('payments')->insert([
                        'tenant_id' => $tenant->id,
                        'invoice_id' => $invoiceId,
                        'order_id' => $orderId,
                        'method' => ['cash', 'card', 'bkash'][array_rand(['cash', 'card', 'bkash'])],
                        'status' => 'completed',
                        'amount' => $subtotal + $tax,
                        'currency' => 'BDT',
                        'created_at' => $created,
                        'updated_at' => $created,
                    ]);
                }

                if ($i % 7 === 0) {
                    Pickup::query()->create([
                        'tenant_id' => $tenant->id,
                        'branch_id' => $branchId,
                        'customer_id' => $customerIds[array_rand($customerIds)],
                        'order_id' => $orderId,
                        'driver_id' => $driverId,
                        'number' => 'PU-'.$i,
                        'status' => 'completed',
                        'scheduled_at' => $created,
                        'completed_at' => $created->copy()->addHour(),
                        'address' => 'Demo Address '.$i,
                        'otp' => (string) random_int(1000, 9999),
                        'cod_amount' => 0,
                    ]);
                    Delivery::query()->create([
                        'tenant_id' => $tenant->id,
                        'branch_id' => $branchId,
                        'customer_id' => $customerIds[array_rand($customerIds)],
                        'order_id' => $orderId,
                        'driver_id' => $driverId,
                        'number' => 'DL-'.$i,
                        'status' => 'completed',
                        'scheduled_at' => $created->copy()->addDays(2),
                        'completed_at' => $created->copy()->addDays(2)->addHour(),
                        'address' => 'Demo Address '.$i,
                        'otp' => (string) random_int(1000, 9999),
                        'cod_collected' => 0,
                    ]);
                }
            }
        }

        for ($i = 1; $i <= 20; $i++) {
            Employee::query()->updateOrCreate(
                ['tenant_id' => $tenant->id, 'employee_code' => 'EMP-'.str_pad((string) $i, 3, '0', STR_PAD_LEFT)],
                [
                    'branch_id' => $branchId,
                    'name' => "Employee {$i}",
                    'department' => ['Production', 'Front Desk', 'Logistics', 'HR'][$i % 4],
                    'designation' => 'Staff',
                    'joined_at' => now()->subMonths(random_int(1, 24)),
                    'base_salary' => random_int(2000000, 5000000),
                    'is_active' => true,
                ]
            );
        }

        TenantContext::clear();
    }
}
