<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = [
            'super_admin', 'support_agent', 'business_owner', 'business_manager', 'branch_manager',
            'cashier', 'tagger', 'production_operator', 'quality_controller', 'driver', 'dispatcher',
            'accountant', 'inventory_keeper', 'hr_officer', 'marketing_officer', 'customer',
            'corporate_customer', 'franchise_owner', 'vendor', 'supplier',
        ];

        foreach ($roles as $role) {
            Role::findOrCreate($role);
        }

        foreach (array_keys(config('laundry-modules.modules')) as $key) {
            foreach (['view', 'create', 'update', 'delete'] as $ability) {
                Permission::findOrCreate("{$key}.{$ability}");
            }
        }

        Role::findByName('super_admin')->givePermissionTo(Permission::all());
        Role::findByName('business_owner')->givePermissionTo(Permission::all());
    }
}
