<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');
        $sparkle = Tenant::query()->where('slug', 'sparkle-dry-cleaners')->firstOrFail();
        $branchId = $sparkle->branches()->where('code', 'GUL')->value('id');

        $platform = [
            ['email' => 'superadmin@demo.test', 'name' => 'Platform Super Admin', 'role' => 'super_admin', 'platform' => true],
            ['email' => 'support@demo.test', 'name' => 'Support Agent', 'role' => 'support_agent', 'platform' => true],
        ];

        foreach ($platform as $row) {
            $user = User::query()->updateOrCreate(['email' => $row['email']], [
                'name' => $row['name'],
                'password' => $password,
                'is_platform_user' => true,
                'email_verified_at' => now(),
            ]);
            $user->syncRoles([$row['role']]);
        }

        $tenantUsers = [
            ['email' => 'owner@demo.test', 'name' => 'Business Owner', 'role' => 'business_owner'],
            ['email' => 'manager@demo.test', 'name' => 'Business Manager', 'role' => 'business_manager'],
            ['email' => 'branch@demo.test', 'name' => 'Branch Manager', 'role' => 'branch_manager'],
            ['email' => 'cashier@demo.test', 'name' => 'Cashier', 'role' => 'cashier'],
            ['email' => 'tagger@demo.test', 'name' => 'Garment Tagger', 'role' => 'tagger'],
            ['email' => 'production@demo.test', 'name' => 'Production Operator', 'role' => 'production_operator'],
            ['email' => 'qc@demo.test', 'name' => 'Quality Controller', 'role' => 'quality_controller'],
            ['email' => 'driver@demo.test', 'name' => 'Driver', 'role' => 'driver'],
            ['email' => 'dispatcher@demo.test', 'name' => 'Dispatcher', 'role' => 'dispatcher'],
            ['email' => 'accountant@demo.test', 'name' => 'Accountant', 'role' => 'accountant'],
            ['email' => 'inventory@demo.test', 'name' => 'Inventory Keeper', 'role' => 'inventory_keeper'],
            ['email' => 'hr@demo.test', 'name' => 'HR Officer', 'role' => 'hr_officer'],
            ['email' => 'marketing@demo.test', 'name' => 'Marketing Officer', 'role' => 'marketing_officer'],
            ['email' => 'customer@demo.test', 'name' => 'Demo Customer', 'role' => 'customer'],
            ['email' => 'corporate@demo.test', 'name' => 'Corporate Customer', 'role' => 'corporate_customer'],
            ['email' => 'franchise@demo.test', 'name' => 'Franchise Owner', 'role' => 'franchise_owner'],
            ['email' => 'vendor@demo.test', 'name' => 'Vendor Laundry', 'role' => 'vendor'],
            ['email' => 'supplier@demo.test', 'name' => 'Supplier', 'role' => 'supplier'],
        ];

        foreach ($tenantUsers as $row) {
            $user = User::query()->updateOrCreate(['email' => $row['email']], [
                'name' => $row['name'],
                'password' => $password,
                'tenant_id' => $sparkle->id,
                'branch_id' => $branchId,
                'is_platform_user' => false,
                'locale' => 'en',
                'email_verified_at' => now(),
            ]);
            $user->syncRoles([$row['role']]);
        }
    }
}
