<?php

declare(strict_types=1);

namespace App\Filament\Staff\Pages;

use App\Models\CatalogItem;
use App\Models\CatalogService;
use App\Models\Customer;
use App\Services\Orders\CreateOrderAction;
use App\Services\Tagging\TagGarmentAction;
use App\Support\Modules\ModuleManager;
use App\Support\Tenancy\TenantContext;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class PosTerminal extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationLabel = 'POS';

    protected static string|\UnitEnum|null $navigationGroup = 'Operations';

    protected string $view = 'filament.staff.pages.pos-terminal';

    public ?int $customer_id = null;

    public string $itemName = 'Shirt';

    public int $quantity = 1;

    public int $unitPrice = 500;

    public ?int $catalog_item_id = null;

    public ?int $catalog_service_id = null;

    public array $cart = [];

    public static function shouldRegisterNavigation(): bool
    {
        $tenant = TenantContext::tenant() ?? auth()->user()?->tenant;

        return $tenant !== null && app(ModuleManager::class)->isEnabled('orders', $tenant);
    }

    public function addToCart(): void
    {
        $this->cart[] = [
            'name' => $this->itemName,
            'quantity' => $this->quantity,
            'unit_price' => $this->unitPrice,
            'catalog_item_id' => $this->catalog_item_id,
            'catalog_service_id' => $this->catalog_service_id,
        ];
        $this->quantity = 1;
    }

    public function checkout(): void
    {
        $tenant = TenantContext::tenant() ?? auth()->user()?->tenant;
        $branchId = auth()->user()?->branch_id ?? $tenant?->branches()->value('id');
        abort_unless($tenant && $branchId, 422);

        if ($this->cart === []) {
            Notification::make()->title('Cart is empty')->danger()->send();

            return;
        }

        $order = app(CreateOrderAction::class)->handle($tenant, [
            'branch_id' => $branchId,
            'customer_id' => $this->customer_id,
            'type' => 'counter',
            'items' => $this->cart,
        ], auth()->id());

        foreach ($order->items as $item) {
            for ($i = 0; $i < $item->quantity; $i++) {
                app(TagGarmentAction::class)->handle($order, [
                    'order_item_id' => $item->id,
                    'color' => null,
                ]);
            }
        }

        $this->cart = [];
        Notification::make()->title('Order '.$order->number.' created with tags')->success()->send();
    }

    public function getCustomersProperty()
    {
        return Customer::query()->orderBy('name')->limit(100)->get();
    }

    public function getItemsProperty()
    {
        return CatalogItem::query()->where('is_active', true)->orderBy('name')->limit(100)->get();
    }

    public function getServicesProperty()
    {
        return CatalogService::query()->where('is_active', true)->orderBy('name')->limit(100)->get();
    }
}
