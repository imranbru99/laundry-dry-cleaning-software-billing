<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Tenant;
use App\Services\Billing\RecordPaymentAction;
use App\Services\Orders\CreateOrderAction;
use App\Services\Tagging\TagGarmentAction;
use App\Support\Tenancy\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_pos_order_tagging_and_partial_payment(): void
    {
        $tenant = Tenant::query()->create([
            'name' => 'Flow Laundry',
            'slug' => 'flow-laundry',
            'currency' => 'BDT',
            'locale' => 'bn',
            'tax_mode' => 'exclusive',
            'settings' => ['tax_rate_bps' => 0],
            'is_active' => true,
        ]);
        $branch = Branch::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Main',
            'slug' => 'main',
            'code' => 'MAIN',
            'is_active' => true,
        ]);
        TenantContext::set($tenant, $branch);

        $order = app(CreateOrderAction::class)->handle($tenant, [
            'branch_id' => $branch->id,
            'type' => 'counter',
            'items' => [
                ['name' => 'Shirt', 'quantity' => 2, 'unit_price' => 300],
            ],
        ]);

        $this->assertSame(600, $order->grand_total);
        $tag = app(TagGarmentAction::class)->handle($order, ['order_item_id' => $order->items->first()->id]);
        $this->assertNotEmpty($tag->tag_code);

        app(RecordPaymentAction::class)->handle($order, 'cash', 200);
        $this->assertSame(200, $order->fresh()->amount_paid);

        TenantContext::clear();
    }
}
