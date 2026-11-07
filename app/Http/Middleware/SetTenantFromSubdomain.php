<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Support\Tenancy\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenantFromSubdomain
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (TenantContext::tenant() === null) {
            $host = $request->getHost();
            $parts = explode('.', $host);

            if (count($parts) >= 3) {
                $subdomain = $parts[0];
                $tenant = Tenant::query()
                    ->where('subdomain', $subdomain)
                    ->where('is_active', true)
                    ->first();

                if ($tenant !== null) {
                    TenantContext::setTenant($tenant);
                }
            }
        }

        return $next($request);
    }
}
