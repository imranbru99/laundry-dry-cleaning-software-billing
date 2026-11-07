<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Orders\CreateOrderAction;
use App\Support\Modules\ModuleManager;
use App\Support\Tenancy\TenantContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->bootTenant($request);
        abort_unless(app(ModuleManager::class)->isEnabled('orders', TenantContext::tenant()), 403);

        return response()->json(
            Order::query()->latest()->paginate(50)
        );
    }

    public function store(Request $request, CreateOrderAction $action): JsonResponse
    {
        $this->bootTenant($request);
        abort_unless(app(ModuleManager::class)->isEnabled('orders', TenantContext::tenant()), 403);

        $data = $request->validate([
            'branch_id' => ['required', 'integer'],
            'customer_id' => ['nullable', 'integer'],
            'type' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.name' => ['required', 'string'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'integer', 'min:0'],
        ]);

        $order = $action->handle(TenantContext::tenant(), $data, $request->user()->id);

        return response()->json($order, 201);
    }

    public function show(Request $request, Order $order): JsonResponse
    {
        $this->bootTenant($request);
        abort_unless((int) $order->tenant_id === (int) TenantContext::tenantId(), 404);

        return response()->json($order->load('items', 'garmentTags'));
    }

    private function bootTenant(Request $request): void
    {
        $user = $request->user();
        abort_unless($user?->tenant, 403);
        TenantContext::set($user->tenant, $user->branch);
    }
}
