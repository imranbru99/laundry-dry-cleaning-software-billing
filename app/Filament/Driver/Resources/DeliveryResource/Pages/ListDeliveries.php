<?php

declare(strict_types=1);

namespace App\Filament\Driver\Resources\DeliveryResource\Pages;

use App\Filament\Driver\Resources\DeliveryResource;
use Filament\Resources\Pages\ListRecords;

class ListDeliveries extends ListRecords
{
    protected static string $resource = DeliveryResource::class;
}
