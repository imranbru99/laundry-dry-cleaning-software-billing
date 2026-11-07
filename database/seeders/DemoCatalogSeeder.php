<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\CatalogItem;
use App\Models\CatalogService;
use App\Models\PriceMatrix;
use App\Models\ServiceCategory;
use App\Models\Tenant;
use Illuminate\Database\Seeder;
use ImranDev\UniversalSlug\Facades\UniversalSlug;

class DemoCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::query()->where('slug', 'sparkle-dry-cleaners')->firstOrFail();

        $categories = ['Garments', 'Formal Wear', 'Home Textiles', 'Leather & Shoes', 'Specialty'];
        $categoryIds = [];
        foreach ($categories as $i => $name) {
            $categoryIds[$name] = ServiceCategory::query()->updateOrCreate(
                ['tenant_id' => $tenant->id, 'slug' => UniversalSlug::generate($name)],
                ['name' => $name, 'name_i18n' => ['en' => $name, 'bn' => $name, 'ar' => $name], 'sort_order' => $i, 'is_active' => true]
            )->id;
        }

        $services = [
            ['Wash', 'wash'], ['Dry Clean', 'dry-clean'], ['Iron', 'iron'], ['Press', 'press'],
            ['Fold', 'fold'], ['Stain Removal', 'stain-removal'], ['Alterations', 'alterations'],
        ];
        $serviceIds = [];
        foreach ($services as [$name, $code]) {
            $serviceIds[$code] = CatalogService::query()->updateOrCreate(
                ['tenant_id' => $tenant->id, 'slug' => $code],
                ['name' => $name, 'code' => $code, 'default_tat_hours' => 48, 'is_active' => true]
            )->id;
        }

        $items = [
            ['Shirt', 'Garments', 300], ['Suit', 'Formal Wear', 1200], ['Saree', 'Garments', 800],
            ['Abaya', 'Garments', 700], ['Thobe', 'Garments', 650], ['Bedding Set', 'Home Textiles', 1500],
            ['Curtain', 'Home Textiles', 2000], ['Carpet', 'Home Textiles', 5000], ['Leather Jacket', 'Leather & Shoes', 2500],
            ['Shoes', 'Leather & Shoes', 900], ['Handbag', 'Leather & Shoes', 1100], ['Wedding Dress', 'Specialty', 8000],
        ];

        foreach ($items as [$name, $cat, $base]) {
            $item = CatalogItem::query()->updateOrCreate(
                ['tenant_id' => $tenant->id, 'slug' => UniversalSlug::generate($name)],
                [
                    'service_category_id' => $categoryIds[$cat],
                    'name' => $name,
                    'name_i18n' => ['en' => $name, 'bn' => $name, 'ar' => $name],
                    'unit' => 'piece',
                    'is_active' => true,
                ]
            );

            foreach ($serviceIds as $code => $serviceId) {
                $multiplier = $code === 'dry-clean' ? 1.0 : ($code === 'wash' ? 0.7 : 0.4);
                PriceMatrix::query()->updateOrCreate(
                    [
                        'tenant_id' => $tenant->id,
                        'catalog_item_id' => $item->id,
                        'catalog_service_id' => $serviceId,
                        'customer_id' => null,
                        'branch_id' => null,
                    ],
                    [
                        'price' => (int) round($base * $multiplier),
                        'currency' => $tenant->currency,
                    ]
                );
            }
        }
    }
}
