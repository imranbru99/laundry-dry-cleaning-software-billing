<?php

declare(strict_types=1);

namespace App\Filament\Portal\Resources\OrderResource\Pages;

use App\Filament\Portal\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;
}
