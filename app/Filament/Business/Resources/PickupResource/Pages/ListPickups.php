<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\PickupResource\Pages;

use App\Filament\Business\Resources\PickupResource;
use Filament\Resources\Pages\ListRecords;

class ListPickups extends ListRecords
{
    protected static string $resource = PickupResource::class;
}
