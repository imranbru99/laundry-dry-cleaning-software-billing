<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\CatalogItemResource\Pages;

use App\Filament\Business\Resources\CatalogItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCatalogItem extends CreateRecord
{
    protected static string $resource = CatalogItemResource::class;
}
