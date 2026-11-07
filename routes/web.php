<?php

declare(strict_types=1);

use App\Http\Controllers\PublicSiteController;
use App\Models\Pickup;
use App\Models\Tenant;
use App\Support\Tenancy\TenantContext;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', [PublicSiteController::class, 'home']);
Route::get('/features', [PublicSiteController::class, 'features']);
Route::get('/pricing', [PublicSiteController::class, 'pricing']);
Route::get('/demo', [PublicSiteController::class, 'demo']);
Route::get('/demo/login/{email}', [PublicSiteController::class, 'demoLogin'])->where('email', '.*');
Route::get('/services', [PublicSiteController::class, 'services']);
Route::get('/track', [PublicSiteController::class, 'track']);
Route::get('/track/{code}', [PublicSiteController::class, 'trackTag']);
Route::get('/book-pickup', [PublicSiteController::class, 'bookPickup']);
Route::post('/book-pickup', function () {
    $tenant = Tenant::query()->where('slug', 'sparkle-dry-cleaners')->firstOrFail();
    TenantContext::set($tenant);
    $branchId = $tenant->branches()->value('id');
    $pickup = Pickup::query()->create([
        'tenant_id' => $tenant->id,
        'branch_id' => $branchId,
        'number' => 'PU-WEB-'.strtoupper(Str::random(6)),
        'status' => 'scheduled',
        'scheduled_at' => request('scheduled_at'),
        'address' => request('address'),
        'otp' => (string) random_int(1000, 9999),
        'meta' => ['guest_name' => request('name'), 'guest_phone' => request('phone')],
    ]);

    return redirect('/track?q='.$pickup->number)->with('status', 'Pickup booked');
});

Route::get('/pages/{page}', [PublicSiteController::class, 'page']);
