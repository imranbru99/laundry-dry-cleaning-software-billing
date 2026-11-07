<?php

declare(strict_types=1);

namespace App\Filament\Staff\Resources\GarmentTagResource\Pages;

use App\Filament\Staff\Resources\GarmentTagResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGarmentTag extends CreateRecord
{
    protected static string $resource = GarmentTagResource::class;
}
