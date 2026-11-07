<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\CmsPage;
use App\Models\Coupon;
use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use ImranDev\UniversalSlug\Facades\UniversalSlug;

class CmsAndMarketingSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::query()->where('slug', 'sparkle-dry-cleaners')->firstOrFail();

        $pages = [
            ['Home', 'home'], ['Features', 'features'], ['Pricing', 'pricing'], ['About', 'about'],
            ['Contact', 'contact'], ['FAQ', 'faq'], ['Terms', 'terms'], ['Privacy', 'privacy'],
        ];
        foreach ($pages as [$title, $slug]) {
            CmsPage::query()->updateOrCreate(
                ['tenant_id' => $tenant->id, 'slug' => $slug, 'locale' => 'en'],
                [
                    'title' => $title,
                    'title_i18n' => ['en' => $title, 'bn' => $title, 'ar' => $title],
                    'body' => "Demo content for {$title}",
                    'is_published' => true,
                    'published_at' => now(),
                ]
            );
        }

        foreach ([
            ['How garment tagging works', 'how-garment-tagging-works'],
            ['Pickup and delivery best practices', 'pickup-delivery-best-practices'],
            ['AI for laundry shops', 'ai-for-laundry-shops'],
        ] as [$title, $slug]) {
            DB::table('blog_posts')->updateOrInsert(
                ['tenant_id' => $tenant->id, 'slug' => $slug, 'locale' => 'en'],
                [
                    'title' => $title,
                    'excerpt' => $title,
                    'body' => $title.' — multilingual demo article.',
                    'is_published' => true,
                    'published_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        foreach ([
            ['How do I track my order?', 'Use the track page with your order or tag number.'],
            ['Do you support Bangla invoices?', 'Yes — PDFs render Bangla, Arabic, Hindi and more via unicode-pdf.'],
            ['Can I turn modules off?', 'Yes — Module Center lets each business toggle modules without losing data.'],
        ] as $i => [$q, $a]) {
            DB::table('faqs')->updateOrInsert(
                ['tenant_id' => $tenant->id, 'question' => $q, 'locale' => 'en'],
                ['answer' => $a, 'sort_order' => $i, 'created_at' => now(), 'updated_at' => now()]
            );
        }

        Coupon::query()->updateOrCreate(
            ['tenant_id' => $tenant->id, 'code' => 'WELCOME10'],
            [
                'type' => 'percent',
                'value' => 10,
                'min_spend' => 1000,
                'is_active' => true,
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addYear(),
            ]
        );
    }
}
