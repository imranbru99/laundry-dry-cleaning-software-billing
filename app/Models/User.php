<?php

declare(strict_types=1);

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'tenant_id', 'branch_id', 'phone',
        'locale', 'timezone', 'is_platform_user', 'two_factor_secret',
        'two_factor_recovery_codes', 'two_factor_confirmed_at', 'avatar_path',
    ];

    protected $hidden = [
        'password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_platform_user' => 'boolean',
            'two_factor_confirmed_at' => 'datetime',
            'two_factor_recovery_codes' => 'encrypted:array',
            'two_factor_secret' => 'encrypted',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'platform' => $this->is_platform_user || $this->hasAnyRole(['super_admin', 'support_agent']),
            'business' => $this->tenant_id !== null && $this->hasAnyRole([
                'business_owner', 'business_manager', 'branch_manager', 'accountant',
                'hr_officer', 'marketing_officer', 'inventory_keeper', 'franchise_owner',
            ]),
            'staff' => $this->tenant_id !== null && $this->hasAnyRole([
                'cashier', 'tagger', 'production_operator', 'quality_controller',
                'dispatcher', 'branch_manager', 'business_manager', 'business_owner',
            ]),
            'driver' => $this->hasRole('driver') || $this->hasRole('dispatcher'),
            'portal' => $this->hasAnyRole(['customer', 'corporate_customer']),
            'admin' => $this->is_platform_user || $this->hasRole('super_admin'),
            default => false,
        };
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->tenant_id ? Tenant::query()->whereKey($this->tenant_id)->get() : collect();
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return (int) $this->tenant_id === (int) $tenant->getKey() || $this->is_platform_user;
    }
}