<?php

declare(strict_types=1);

namespace App\Filament\Driver\Resources\PickupResource\Pages;

use App\Filament\Driver\Resources\PickupResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePickup extends CreateRecord
{
    protected static string $resource = PickupResource::class;
}
