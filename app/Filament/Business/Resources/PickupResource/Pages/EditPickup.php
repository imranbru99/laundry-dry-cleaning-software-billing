<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\PickupResource\Pages;

use App\Filament\Business\Resources\PickupResource;
use Filament\Resources\Pages\EditRecord;

class EditPickup extends EditRecord
{
    protected static string $resource = PickupResource::class;
}
