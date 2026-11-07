<?php

declare(strict_types=1);

namespace App\Support\Tenancy;

use App\Models\Branch;
use App\Models\Tenant;

final class TenantContext
{
    private static ?Tenant $tenant = null;

    private static ?Branch $branch = null;

    public static function set(?Tenant $tenant, ?Branch $branch = null): void
    {
        self::$tenant = $tenant;
        self::$branch = $branch;
    }

    public static function tenant(): ?Tenant
    {
        return self::$tenant;
    }

    public static function tenantId(): ?int
    {
        return self::$tenant?->id;
    }

    public static function branch(): ?Branch
    {
        return self::$branch;
    }

    public static function branchId(): ?int
    {
        return self::$branch?->id;
    }

    public static function clear(): void
    {
        self::$tenant = null;
        self::$branch = null;
    }
}