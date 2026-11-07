<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ModuleController;
use App\Support\Tenancy\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('/me', function (Request $request) {
            $user = $request->user();
            if ($user->tenant) {
                TenantContext::set($user->tenant, $user->branch);
            }

            return $user->load('roles');
        });

        Route::get('/modules', [ModuleController::class, 'index']);
        Route::post('/modules/{key}/enable', [ModuleController::class, 'enable']);
        Route::post('/modules/{key}/disable', [ModuleController::class, 'disable']);

        Route::get('/orders', [OrderController::class, 'index']);
        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
    });
});
