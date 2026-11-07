<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Support\Modules\ModuleManager;
use App\Support\Tenancy\TenantContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index(Request $request, ModuleManager $modules): JsonResponse
    {
        $tenant = $request->user()->tenant;
        abort_unless($tenant, 403);
        TenantContext::set($tenant);

        return response()->json([
            'enabled' => $modules->enabledKeys($tenant),
            'registry' => config('laundry-modules.registry'),
        ]);
    }

    public function enable(Request $request, string $key, ModuleManager $modules): JsonResponse
    {
        $tenant = $request->user()->tenant;
        abort_unless($tenant, 403);
        $modules->enable($key, $tenant, true);

        return response()->json(['enabled' => true, 'key' => $key]);
    }

    public function disable(Request $request, string $key, ModuleManager $modules): JsonResponse
    {
        $tenant = $request->user()->tenant;
        abort_unless($tenant, 403);
        $dependents = $modules->disable($key, $tenant);

        return response()->json(['enabled' => false, 'key' => $key, 'dependents' => $dependents]);
    }
}
