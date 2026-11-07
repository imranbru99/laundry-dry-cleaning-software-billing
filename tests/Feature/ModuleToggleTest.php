<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\Tenant;
use App\Support\Modules\ModuleManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleToggleTest extends TestCase
{
    use RefreshDatabase;

    public function test_core_is_always_enabled_and_loyalty_can_toggle(): void
    {
        app(ModuleManager::class)->syncRegistryFromConfig();

        $plan = Plan::query()->create([
            'name' => 'Enterprise',
            'slug' => 'enterprise-test',
            'price_monthly' => 0,
            'price_yearly' => 0,
            'modules' => ['*'],
            'is_active' => true,
        ]);

        $tenant = Tenant::query()->create([
            'name' => 'Toggle Co',
            'slug' => 'toggle-co',
            'currency' => 'USD',
            'locale' => 'en',
            'plan_id' => $plan->id,
            'is_active' => true,
        ]);

        $manager = app(ModuleManager::class);
        $this->assertTrue($manager->isEnabled('core', $tenant));

        $manager->enable('loyalty', $tenant, true);
        $this->assertTrue($manager->isEnabled('loyalty', $tenant));
        $this->assertTrue($manager->isEnabled('customers-crm', $tenant));

        $manager->disable('loyalty', $tenant);
        $this->assertFalse($manager->isEnabled('loyalty', $tenant));
    }
}
