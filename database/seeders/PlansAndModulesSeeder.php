<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Plan;
use App\Support\Modules\ModuleManager;
use Illuminate\Database\Seeder;

class PlansAndModulesSeeder extends Seeder
{
    public function run(): void
    {
        app(ModuleManager::class)->syncRegistryFromConfig();

        $allModules = array_keys(config('laundry-modules.modules'));

        Plan::query()->updateOrCreate(['slug' => 'starter'], [
            'name' => 'Starter',
            'description' => 'POS, tagging and billing for single-counter shops',
            'price_monthly' => 2900,
            'price_yearly' => 29000,
            'currency' => 'USD',
            'modules' => ['core', 'customers-crm', 'service-catalog', 'orders-pos', 'garment-tagging', 'billing', 'website-cms'],
            'limits' => ['branches' => 1, 'users' => 5],
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Plan::query()->updateOrCreate(['slug' => 'growth'], [
            'name' => 'Growth',
            'description' => 'Pickup/delivery, loyalty and multi-branch ready',
            'price_monthly' => 7900,
            'price_yearly' => 79000,
            'currency' => 'USD',
            'modules' => [
                'core', 'customers-crm', 'service-catalog', 'orders-pos', 'garment-tagging', 'billing',
                'pickup-delivery', 'loyalty', 'subscriptions', 'communication', 'reports', 'website-cms',
            ],
            'limits' => ['branches' => 5, 'users' => 25],
            'is_active' => true,
            'sort_order' => 2,
        ]);

        Plan::query()->updateOrCreate(['slug' => 'enterprise'], [
            'name' => 'Enterprise',
            'description' => 'All modules including B2B linen, HR, accounting and AI',
            'price_monthly' => 19900,
            'price_yearly' => 199000,
            'currency' => 'USD',
            'modules' => $allModules,
            'limits' => ['branches' => 100, 'users' => 500],
            'is_active' => true,
            'sort_order' => 3,
        ]);
    }
}
