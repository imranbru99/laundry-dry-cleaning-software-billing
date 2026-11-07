<?php

declare(strict_types=1);

namespace App\Filament\Business\Pages;

use App\Support\Modules\ModuleManager;
use App\Support\Tenancy\TenantContext;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class ModuleCenter extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $navigationLabel = 'Module Center';

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.business.pages.module-center';

    public array $modules = [];

    public function mount(): void
    {
        $this->refreshModules();
    }

    public function getTitle(): string|Htmlable
    {
        return 'Module Center';
    }

    public function refreshModules(): void
    {
        $tenant = TenantContext::tenant() ?? auth()->user()?->tenant;
        $manager = app(ModuleManager::class);
        $this->modules = [];

        foreach (config('laundry-modules.modules') as $key => $meta) {
            $this->modules[] = [
                'key' => $key,
                'name' => $meta['name'],
                'always_on' => (bool) ($meta['core'] ?? false),
                'dependencies' => $meta['dependencies'] ?? [],
                'enabled' => $tenant ? $manager->isEnabled($key, $tenant) : false,
                'dependents' => $tenant ? $manager->dependentsOf($key, $tenant->id) : [],
            ];
        }
    }

    public function toggle(string $key): void
    {
        $tenant = TenantContext::tenant() ?? auth()->user()?->tenant;
        abort_unless($tenant, 403);

        $manager = app(ModuleManager::class);
        $meta = config("laundry-modules.modules.{$key}");
        abort_if(($meta['core'] ?? false) === true, 422, 'Core cannot be disabled.');

        if ($manager->isEnabled($key, $tenant)) {
            $dependents = $manager->disable($key, $tenant);
            Notification::make()
                ->title("Disabled {$meta['name']}")
                ->body($dependents ? 'Dependents still on: '.implode(', ', $dependents) : null)
                ->warning()
                ->send();
        } else {
            $manager->enable($key, $tenant, true);
            Notification::make()->title("Enabled {$meta['name']}")->success()->send();
        }

        $this->refreshModules();
    }
}
