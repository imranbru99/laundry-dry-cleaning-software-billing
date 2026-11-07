<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\OrderResource\Pages;

use App\Filament\Business\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;
}
