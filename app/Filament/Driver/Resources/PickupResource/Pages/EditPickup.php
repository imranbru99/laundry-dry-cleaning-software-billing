<?php

declare(strict_types=1);

namespace App\Filament\Driver\Resources\PickupResource\Pages;

use App\Filament\Driver\Resources\PickupResource;
use Filament\Resources\Pages\EditRecord;

class EditPickup extends EditRecord
{
    protected static string $resource = PickupResource::class;
}
