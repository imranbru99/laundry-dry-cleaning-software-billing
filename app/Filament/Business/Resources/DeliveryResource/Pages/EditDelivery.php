<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\DeliveryResource\Pages;

use App\Filament\Business\Resources\DeliveryResource;
use Filament\Resources\Pages\EditRecord;

class EditDelivery extends EditRecord
{
    protected static string $resource = DeliveryResource::class;
}
