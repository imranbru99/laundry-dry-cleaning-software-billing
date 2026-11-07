<?php

declare(strict_types=1);

namespace App\Providers;

use App\Support\Modules\ModuleManager;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use ImranDevBD\LaravelAdminPanel\Facades\AdminPanel;
use ImranDevBD\LaravelAdminPanel\Panel\Panel;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ModuleManager::class);
    }

    public function boot(): void
    {
        if (class_exists(AdminPanel::class)) {
            AdminPanel::registerPanel(
                Panel::make('laundry-platform')
                    ->path('platform/system')
                    ->guard('web')
            );
        }

        if (Schema::hasTable('modules') && Schema::hasColumn('modules', 'sort_order')) {
            try {
                $this->app->make(ModuleManager::class)->syncRegistryFromConfig();
            } catch (\Throwable) {
                // Skip during migrate:fresh mid-flight or incomplete schema.
            }
        }
    }
}
