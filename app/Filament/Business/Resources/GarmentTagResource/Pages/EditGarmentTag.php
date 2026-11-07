<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\GarmentTagResource\Pages;

use App\Filament\Business\Resources\GarmentTagResource;
use Filament\Resources\Pages\EditRecord;

class EditGarmentTag extends EditRecord
{
    protected static string $resource = GarmentTagResource::class;
}
