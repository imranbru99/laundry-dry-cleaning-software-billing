<?php

declare(strict_types=1);

namespace App\Filament\Driver\Resources\PickupResource\Pages;

use App\Filament\Driver\Resources\PickupResource;
use Filament\Resources\Pages\ListRecords;

class ListPickups extends ListRecords
{
    protected static string $resource = PickupResource::class;
}
