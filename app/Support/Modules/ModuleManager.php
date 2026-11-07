<?php

declare(strict_types=1);

namespace App\Support\Modules;

use App\Models\Branch;
use App\Models\Module;
use App\Models\Tenant;
use App\Models\TenantModule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class ModuleManager
{
    /** Short aliases used by Filament resources → registry keys */
    private const ALIASES = [
        'crm' => 'customers-crm',
        'catalog' => 'service-catalog',
        'orders' => 'orders-pos',
        'tagging' => 'garment-tagging',
        'logistics' => 'pickup-delivery',
        'billing' => 'billing',
        'subscriptions' => 'subscriptions',
        'loyalty' => 'loyalty',
        'inventory' => 'inventory',
        'accounting' => 'accounting',
        'hr' => 'hr-attendance',
        'franchise' => 'franchise',
        'b2b' => 'b2b-corporate',
        'self_service' => 'self-service',
        'communication' => 'communication',
        'ai_suite' => 'ai-suite',
        'reports' => 'reports',
        'cms' => 'website-cms',
        'integrations' => 'integrations',
        'support' => 'support-compliance',
        'production' => 'production',
        'core' => 'core',
    ];

    public function normalize(string $key): string
    {
        return self::ALIASES[$key] ?? $key;
    }

    public function isEnabled(string $key, Tenant|int|null $tenant = null, Branch|int|null $branch = null): bool
    {
        $key = $this->normalize($key);
        $definition = config("laundry-modules.modules.{$key}");
        if (($definition['core'] ?? false) === true) {
            return true;
        }

        $tenantId = $tenant instanceof Tenant ? $tenant->id : $tenant;
        if ($tenantId === null) {
            return false;
        }

        $module = Module::query()->where('key', $key)->where('is_active', true)->first();
        if ($module === null) {
            return false;
        }

        if (! $this->isAllowedByPlan($key, $tenantId)) {
            return false;
        }

        $branchId = $branch instanceof Branch ? $branch->id : $branch;
        $cacheKey = "tm:{$tenantId}:".($branchId ?? 'all').":{$key}";

        return (bool) Cache::remember($cacheKey, 60, function () use ($tenantId, $module, $branchId): bool {
            if ($branchId !== null) {
                $override = TenantModule::query()
                    ->where('tenant_id', $tenantId)
                    ->where('module_id', $module->id)
                    ->where('branch_id', $branchId)
                    ->first();
                if ($override !== null) {
                    return (bool) ($override->enabled ?? $override->is_enabled);
                }
            }

            $row = TenantModule::query()
                ->where('tenant_id', $tenantId)
                ->where('module_id', $module->id)
                ->whereNull('branch_id')
                ->first();

            return (bool) ($row?->enabled ?? $row?->is_enabled ?? false);
        });
    }

    public function enable(string $key, Tenant|int $tenant, bool $enableDependencies = true, Branch|int|null $branch = null): void
    {
        $key = $this->normalize($key);
        $tenantModel = $tenant instanceof Tenant ? $tenant : Tenant::query()->findOrFail($tenant);
        $module = $this->resolveModule($key);

        if ($enableDependencies) {
            foreach ($this->dependencies($key) as $dep) {
                if ($dep !== $key) {
                    $this->enable($dep, $tenantModel, true, $branch);
                }
            }
        }

        $this->upsert($tenantModel, $module, $branch, true);
        $this->flush($tenantModel->id);
    }

    public function disable(string $key, Tenant|int $tenant, Branch|int|null $branch = null): array
    {
        $key = $this->normalize($key);
        if ($this->isCore($key)) {
            throw new RuntimeException("Module [{$key}] is core and cannot be disabled.");
        }

        $tenantModel = $tenant instanceof Tenant ? $tenant : Tenant::query()->findOrFail($tenant);
        $module = $this->resolveModule($key);
        $dependents = $this->dependentsOf($key, $tenantModel->id, $branch);
        $this->upsert($tenantModel, $module, $branch, false);
        $this->flush($tenantModel->id);

        return $dependents;
    }

    public function applyPreset(string $preset, Tenant|int $tenant): void
    {
        $keys = config("laundry-modules.presets.{$preset}", config("laundry-modules.business_presets.{$preset}", []));
        foreach ($keys as $key) {
            $this->enable($key, $tenant, true);
        }
    }

    /** @return list<string> */
    public function dependencies(string $key): array
    {
        $key = $this->normalize($key);
        $fromConfig = config("laundry-modules.modules.{$key}.dependencies", []);
        $module = Module::query()->where('key', $key)->first();

        return array_values(array_unique(array_merge(
            $module?->dependencies ?? [],
            is_array($fromConfig) ? $fromConfig : [],
        )));
    }

    /** @return list<string> */
    public function dependentsOf(string $key, int $tenantId, Branch|int|null $branch = null): array
    {
        $key = $this->normalize($key);
        $out = [];
        foreach (array_keys(config('laundry-modules.modules', [])) as $candidate) {
            if ($candidate === $key) {
                continue;
            }
            if (in_array($key, $this->dependencies($candidate), true) && $this->isEnabled($candidate, $tenantId, $branch)) {
                $out[] = $candidate;
            }
        }

        return $out;
    }

    public function enabledKeys(Tenant|int $tenant, Branch|int|null $branch = null): Collection
    {
        $tenantId = $tenant instanceof Tenant ? $tenant->id : $tenant;

        return collect(array_keys(config('laundry-modules.modules', [])))
            ->filter(fn (string $key) => $this->isEnabled($key, $tenantId, $branch))
            ->values();
    }

    public function syncRegistryFromConfig(): void
    {
        DB::transaction(function (): void {
            $sort = 0;
            foreach (config('laundry-modules.modules', []) as $key => $definition) {
                Module::query()->updateOrCreate(
                    ['key' => $key],
                    [
                        'name' => (string) ($definition['name'] ?? $key),
                        'description' => (string) ($definition['description'] ?? ''),
                        'dependencies' => array_values($definition['dependencies'] ?? []),
                        'is_core' => (bool) ($definition['core'] ?? false),
                        'is_active' => true,
                        'sort_order' => $sort++,
                    ]
                );
            }
        });
    }

    private function isCore(string $key): bool
    {
        return (bool) (config("laundry-modules.modules.{$key}.core") ?? false)
            || (bool) Module::query()->where('key', $key)->value('is_core');
    }

    private function resolveModule(string $key): Module
    {
        $module = Module::query()->where('key', $key)->first();
        if ($module) {
            return $module;
        }

        $definition = config("laundry-modules.modules.{$key}");
        if (! is_array($definition)) {
            throw new RuntimeException("Unknown module key [{$key}].");
        }

        return Module::query()->create([
            'key' => $key,
            'name' => (string) ($definition['name'] ?? $key),
            'description' => (string) ($definition['description'] ?? ''),
            'dependencies' => array_values($definition['dependencies'] ?? []),
            'is_core' => (bool) ($definition['core'] ?? false),
            'is_active' => true,
        ]);
    }

    private function upsert(Tenant $tenant, Module $module, Branch|int|null $branch, bool $enabled): void
    {
        $branchId = $branch instanceof Branch ? $branch->id : $branch;
        TenantModule::query()->updateOrCreate(
            [
                'tenant_id' => $tenant->id,
                'module_id' => $module->id,
                'branch_id' => $branchId,
            ],
            [
                'module_key' => $module->key,
                'enabled' => $enabled,
                'is_enabled' => $enabled,
                'enabled_at' => $enabled ? now() : null,
                'disabled_at' => $enabled ? null : now(),
            ]
        );
    }

    private function isAllowedByPlan(string $key, int $tenantId): bool
    {
        $tenant = Tenant::query()->with('plan')->find($tenantId);
        if ($tenant === null) {
            return false;
        }
        if ($this->isCore($key)) {
            return true;
        }
        $planModules = $tenant->plan?->modules;
        if (! is_array($planModules) || $planModules === []) {
            return true;
        }

        return in_array($key, $planModules, true)
            || in_array('*', $planModules, true)
            || in_array(array_search($key, self::ALIASES, true) ?: $key, $planModules, true);
    }

    private function flush(int $tenantId): void
    {
        foreach (array_keys(config('laundry-modules.modules', [])) as $key) {
            Cache::forget("tm:{$tenantId}:all:{$key}");
        }
    }
}
