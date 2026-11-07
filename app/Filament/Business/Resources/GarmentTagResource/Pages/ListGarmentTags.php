<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\GarmentTagResource\Pages;

use App\Filament\Business\Resources\GarmentTagResource;
use Filament\Resources\Pages\ListRecords;

class ListGarmentTags extends ListRecords
{
    protected static string $resource = GarmentTagResource::class;
}
