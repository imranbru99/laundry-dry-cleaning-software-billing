<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Branch;
use App\Models\Tenant;
use App\Support\Tenancy\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenantFromUser
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user !== null && $user->tenant_id !== null) {
            $tenant = Tenant::query()->find($user->tenant_id);
            if ($tenant !== null) {
                $branch = null;
                if ($user->branch_id !== null) {
                    $branch = Branch::query()
                        ->withoutGlobalScopes()
                        ->where('tenant_id', $tenant->id)
                        ->find($user->branch_id);
                }

                TenantContext::set($tenant, $branch);
            }
        }

        try {
            return $next($request);
        } finally {
            TenantContext::clear();
        }
    }
}
