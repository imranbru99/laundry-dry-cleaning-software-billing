# Architecture

## Tenancy

Single database. Every tenant-owned row carries `tenant_id`. Eloquent models use `BelongsToTenant`, which:

1. Applies a global scope when `TenantContext` has a tenant  
2. Auto-fills `tenant_id` on create  

Filament Business/Staff/Driver/Portal panels use `->tenant(Tenant::class)` plus `SetTenantFromFilament` middleware.

## Module system

- Registry: `modules` table + `config/laundry-modules.php`  
- Per-tenant (and optional per-branch) toggles: `tenant_modules`  
- Runtime API: `ModuleManager::isEnabled / enable / disable / applyPreset`  
- Filament short aliases (`crm` → `customers-crm`) kept for resource gating  

When disabled: Module Center / navigation / API / public pages gated; rows remain.

## Panels vs Admin Panel package

Operational CRUD lives in Filament. `AdminPanelBridge` registers a light `/ops` panel from `laravel-admin-panel` for health/diagnostics without duplicating laundry resources.
