<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_accounts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('points')->default(0);
            $table->string('tier')->default('bronze');
            $table->timestamps();
            $table->unique(['tenant_id', 'customer_id']);
        });

        Schema::create('coupons', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('code');
            $table->string('type'); // percent|fixed|bogo|first_order
            $table->unsignedInteger('value')->default(0);
            $table->unsignedBigInteger('min_spend')->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->json('constraints')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['tenant_id', 'code']);
        });

        Schema::create('campaigns', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('channel'); // sms|whatsapp|email|push
            $table->string('status')->default('draft');
            $table->json('segment')->nullable();
            $table->text('content')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('plan_name');
            $table->string('billing_cycle'); // weekly|monthly
            $table->unsignedInteger('allowance')->default(0);
            $table->string('allowance_unit')->default('kg');
            $table->unsignedInteger('used')->default(0);
            $table->boolean('rollover')->default(false);
            $table->string('status')->default('active');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('renews_at')->nullable();
            $table->timestamp('paused_at')->nullable();
            $table->unsignedBigInteger('price')->default(0);
            $table->timestamps();
        });

        Schema::create('contracts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('status')->default('active');
            $table->json('pricing')->nullable();
            $table->json('sla')->nullable();
            $table->date('starts_on')->nullable();
            $table->date('ends_on')->nullable();
            $table->unsignedBigInteger('credit_limit')->default(0);
            $table->timestamps();
        });

        Schema::create('linen_counts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contract_id')->nullable()->constrained()->nullOnDelete();
            $table->string('direction'); // in|out
            $table->date('count_date');
            $table->json('items');
            $table->timestamps();
        });

        Schema::create('cms_pages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->json('title_i18n')->nullable();
            $table->string('slug');
            $table->string('locale', 10)->default('en');
            $table->string('template')->default('default');
            $table->json('sections')->nullable();
            $table->text('body')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->unique(['tenant_id', 'slug', 'locale']);
        });

        Schema::create('blog_posts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->string('locale', 10)->default('en');
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('question');
            $table->text('answer');
            $table->string('locale', 10)->default('en');
            $table->string('category')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('author');
            $table->string('role')->nullable();
            $table->text('content');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('reviews', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });

        Schema::create('support_tickets', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('number');
            $table->string('subject');
            $table->text('body')->nullable();
            $table->string('status')->default('open');
            $table->string('priority')->default('normal');
            $table->timestamps();
        });

        Schema::create('claims', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('garment_tag_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type'); // damage|lost|complaint
            $table->string('status')->default('open');
            $table->unsignedBigInteger('compensation')->default(0);
            $table->text('description')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('ai_outputs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('feature');
            $table->string('provider')->nullable();
            $table->string('model')->nullable();
            $table->json('input')->nullable();
            $table->json('output')->nullable();
            $table->string('status')->default('pending_review');
            $table->boolean('accepted')->default(false);
            $table->unsignedInteger('tokens')->default(0);
            $table->unsignedBigInteger('cost_minor')->default(0);
            $table->timestamps();
        });

        Schema::create('notification_templates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('key');
            $table->string('channel');
            $table->string('locale', 10)->default('en');
            $table->string('subject')->nullable();
            $table->text('body');
            $table->timestamps();
            $table->unique(['tenant_id', 'key', 'channel', 'locale']);
        });

        Schema::create('webhooks', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('url');
            $table->json('events')->nullable();
            $table->string('secret')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('api_keys', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('key_hash');
            $table->string('key_prefix', 12);
            $table->json('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });

        Schema::create('self_service_machines', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code');
            $table->string('type'); // washer|dryer|locker
            $table->string('status')->default('available');
            $table->unsignedBigInteger('cycle_price')->default(0);
            $table->timestamps();
        });

        Schema::create('franchise_agreements', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('partner_name');
            $table->unsignedInteger('royalty_percent')->default(0);
            $table->unsignedInteger('commission_percent')->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('wallets', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('balance')->default(0);
            $table->string('currency', 3)->default('USD');
            $table->timestamps();
            $table->unique(['tenant_id', 'customer_id']);
        });

        Schema::create('cash_drawers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('opened_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('opening_float')->default(0);
            $table->unsignedBigInteger('closing_amount')->nullable();
            $table->unsignedBigInteger('expected_amount')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->json('z_report')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach ([
            'cash_drawers','wallets','franchise_agreements','self_service_machines','api_keys','webhooks',
            'notification_templates','ai_outputs','claims','support_tickets','reviews','testimonials',
            'faqs','blog_posts','cms_pages','linen_counts','contracts','subscriptions','campaigns',
            'coupons','loyalty_accounts',
        ] as $t) {
            Schema::dropIfExists($t);
        }
    }
};