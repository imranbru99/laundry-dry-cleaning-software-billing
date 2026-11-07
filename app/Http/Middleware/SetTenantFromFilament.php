<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\Tenancy\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetTenantFromFilament
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = filament()->getTenant();
        if ($tenant) {
            TenantContext::set($tenant, auth()->user()?->branch);
        }

        return $next($request);
    }
}