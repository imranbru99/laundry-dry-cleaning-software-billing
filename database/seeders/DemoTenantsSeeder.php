<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Plan;
use App\Models\Tenant;
use App\Support\Modules\ModuleManager;
use Illuminate\Database\Seeder;

class DemoTenantsSeeder extends Seeder
{
    public function run(): void
    {
        $enterprise = Plan::query()->where('slug', 'enterprise')->first();
        $starter = Plan::query()->where('slug', 'starter')->first();

        $sparkle = Tenant::query()->updateOrCreate(['slug' => 'sparkle-dry-cleaners'], [
            'name' => 'Sparkle Dry Cleaners',
            'email' => 'hello@sparkle.demo',
            'phone' => '+8801700000001',
            'business_type' => 'full_chain',
            'locale' => 'en',
            'currency' => 'BDT',
            'timezone' => 'Asia/Dhaka',
            'tax_mode' => 'exclusive',
            'country' => 'BD',
            'settings' => ['tax_rate_bps' => 500, 'ai' => ['provider' => 'gemini']],
            'is_demo' => true,
            'is_active' => true,
            'plan_id' => $enterprise?->id,
        ]);

        foreach ([
            ['name' => 'Gulshan Flagship', 'code' => 'GUL', 'is_central_plant' => false],
            ['name' => 'Dhanmondi Outlet', 'code' => 'DHN', 'is_central_plant' => false],
            ['name' => 'Uttara Outlet', 'code' => 'UTT', 'is_central_plant' => false],
            ['name' => 'Central Plant Mirpur', 'code' => 'PLT', 'is_central_plant' => true],
        ] as $branch) {
            Branch::query()->updateOrCreate(
                ['tenant_id' => $sparkle->id, 'code' => $branch['code']],
                [
                    'name' => $branch['name'],
                    'slug' => str($branch['name'])->slug()->toString(),
                    'type' => $branch['is_central_plant'] ? 'plant' : 'outlet',
                    'city' => 'Dhaka',
                    'country' => 'BD',
                    'is_central_plant' => $branch['is_central_plant'],
                    'is_active' => true,
                ]
            );
        }

        $manager = app(ModuleManager::class);
        foreach (array_keys(config('laundry-modules.modules')) as $key) {
            if ($key !== 'core') {
                $manager->enable($key, $sparkle, true);
            }
        }

        $quick = Tenant::query()->updateOrCreate(['slug' => 'quickwash-express'], [
            'name' => 'QuickWash Express',
            'email' => 'hello@quickwash.demo',
            'business_type' => 'wash_fold',
            'locale' => 'en',
            'currency' => 'USD',
            'timezone' => 'UTC',
            'tax_mode' => 'exclusive',
            'country' => 'US',
            'settings' => ['tax_rate_bps' => 800],
            'is_demo' => true,
            'is_active' => true,
            'plan_id' => $starter?->id,
        ]);

        Branch::query()->updateOrCreate(
            ['tenant_id' => $quick->id, 'code' => 'MAIN'],
            ['name' => 'Main Counter', 'slug' => 'main-counter', 'city' => 'Austin', 'country' => 'US', 'is_active' => true]
        );
        foreach (['customers-crm', 'service-catalog', 'orders-pos', 'garment-tagging', 'billing'] as $key) {
            $manager->enable($key, $quick, true);
        }

        $hotel = Tenant::query()->updateOrCreate(['slug' => 'hotellinen-pro'], [
            'name' => 'HotelLinen Pro',
            'email' => 'hello@hotellinen.demo',
            'business_type' => 'hotel_linen',
            'locale' => 'en',
            'currency' => 'USD',
            'timezone' => 'UTC',
            'tax_mode' => 'exclusive',
            'country' => 'AE',
            'settings' => ['tax_rate_bps' => 500],
            'is_demo' => true,
            'is_active' => true,
            'plan_id' => $enterprise?->id,
        ]);

        Branch::query()->updateOrCreate(
            ['tenant_id' => $hotel->id, 'code' => 'HUB'],
            ['name' => 'Dubai Plant', 'slug' => 'dubai-plant', 'city' => 'Dubai', 'country' => 'AE', 'is_central_plant' => true, 'is_active' => true]
        );
        $manager->applyPreset('hotel_linen', $hotel);
    }
}
