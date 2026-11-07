<?php

declare(strict_types=1);

namespace App\Support\Integrations;

use ImranDevBD\LaravelAdminPanel\Facades\AdminPanel;
use ImranDevBD\LaravelAdminPanel\Panel\Panel;

/**
 * Registers a lightweight Ops panel from laravel-admin-panel
 * without duplicating Filament laundry resources.
 */
final class AdminPanelBridge
{
    public function register(): void
    {
        if (! class_exists(AdminPanel::class) || ! class_exists(Panel::class)) {
            return;
        }

        try {
            AdminPanel::registerPanel(
                Panel::make('ops')
                    ->path('ops')
                    ->guard('web')
                    ->brandName('Laundry Ops Health')
            );
        } catch (\Throwable) {
            // Package may already have default panel from install; safe to ignore.
        }
    }
}