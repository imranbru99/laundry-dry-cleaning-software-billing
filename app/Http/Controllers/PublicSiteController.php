<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CatalogItem;
use App\Models\GarmentTag;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Tenant;
use App\Models\User;
use App\Support\Modules\ModuleManager;
use App\Support\Tenancy\TenantContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PublicSiteController extends Controller
{
    private function demoTenant(): Tenant
    {
        $tenant = Tenant::query()->where('slug', 'sparkle-dry-cleaners')->first()
            ?? Tenant::query()->firstOrFail();
        TenantContext::set($tenant);

        return $tenant;
    }

    public function home(): View
    {
        $tenant = $this->demoTenant();

        return view('public.home', [
            'tenant' => $tenant,
            'plans' => Plan::query()->where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function features(): View
    {
        return view('public.features', [
            'modules' => config('laundry-modules.modules'),
            'tenant' => $this->demoTenant(),
        ]);
    }

    public function pricing(): View
    {
        return view('public.pricing', [
            'plans' => Plan::query()->where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function demo(): View
    {
        return view('public.demo', [
            'users' => User::query()->whereIn('email', [
                'superadmin@demo.test', 'owner@demo.test', 'cashier@demo.test', 'driver@demo.test',
                'customer@demo.test', 'corporate@demo.test', 'tagger@demo.test', 'dispatcher@demo.test',
            ])->get(),
        ]);
    }

    public function demoLogin(string $email): RedirectResponse
    {
        $user = User::query()->where('email', $email)->firstOrFail();
        Auth::login($user);

        return match (true) {
            $user->is_platform_user => redirect('/platform'),
            $user->hasRole('driver') => redirect('/driver'),
            $user->hasAnyRole(['cashier', 'tagger', 'production_operator', 'quality_controller', 'dispatcher']) => redirect('/staff'),
            $user->hasAnyRole(['customer', 'corporate_customer']) => redirect('/portal'),
            default => redirect('/business'),
        };
    }

    public function services(): View
    {
        $this->demoTenant();

        return view('public.services', [
            'items' => CatalogItem::query()->with('category')->where('is_active', true)->get(),
        ]);
    }

    public function track(Request $request): View
    {
        $this->demoTenant();
        $code = (string) $request->query('q', '');
        $order = null;
        $tag = null;
        if ($code !== '') {
            $order = Order::query()->where('number', $code)->first();
            $tag = GarmentTag::query()->where('tag_code', $code)->first();
        }

        return view('public.track', compact('code', 'order', 'tag'));
    }

    public function trackTag(string $code): View
    {
        $this->demoTenant();
        $tag = GarmentTag::query()->where('tag_code', $code)->firstOrFail();
        $order = $tag->order ?? Order::query()->find($tag->order_id);

        return view('public.track', ['code' => $code, 'order' => $order, 'tag' => $tag]);
    }

    public function bookPickup(): View
    {
        $tenant = $this->demoTenant();
        abort_unless(app(ModuleManager::class)->isEnabled('logistics', $tenant), 404);

        return view('public.book-pickup', compact('tenant'));
    }

    public function page(string $page): View
    {
        $view = "public.pages.{$page}";
        if (! view()->exists($view)) {
            abort(404);
        }

        return view($view, ['tenant' => $this->demoTenant()]);
    }
}
