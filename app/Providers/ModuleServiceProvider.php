<?php

declare(strict_types=1);

namespace App\Providers;

use App\Support\Integrations\AdminPanelBridge;
use App\Support\Modules\ModuleManager;
use Illuminate\Support\ServiceProvider;

final class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ModuleManager::class);
        $this->mergeConfigFrom(config_path('laundry-modules.php'), 'laundry-modules');
    }

    public function boot(): void
    {
        foreach (glob(app_path('Modules/*/module.json')) ?: [] as $manifest) {
            $json = json_decode((string) file_get_contents($manifest), true);
            if (! is_array($json)) {
                continue;
            }
            // Manifests are loaded into cache/registry at runtime by ModuleManager via config.
        }

        if ($this->app->runningInConsole() === false) {
            app(AdminPanelBridge::class)->register();
        }
    }
}