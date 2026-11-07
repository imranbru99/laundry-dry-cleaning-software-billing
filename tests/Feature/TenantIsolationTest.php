<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Tenant;
use App\Support\Tenancy\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_global_scope_prevents_cross_tenant_customer_leakage(): void
    {
        $a = Tenant::query()->create(['name' => 'A', 'slug' => 'tenant-a', 'currency' => 'USD', 'locale' => 'en']);
        $b = Tenant::query()->create(['name' => 'B', 'slug' => 'tenant-b', 'currency' => 'USD', 'locale' => 'en']);

        TenantContext::set($a);
        Customer::query()->create(['tenant_id' => $a->id, 'name' => 'Alice', 'email' => 'a@example.test']);

        TenantContext::set($b);
        Customer::query()->create(['tenant_id' => $b->id, 'name' => 'Bob', 'email' => 'b@example.test']);

        TenantContext::set($a);
        $this->assertSame(1, Customer::query()->count());
        $this->assertSame('Alice', Customer::query()->first()->name);

        TenantContext::set($b);
        $this->assertSame(1, Customer::query()->count());
        $this->assertSame('Bob', Customer::query()->first()->name);

        TenantContext::clear();
    }
}
