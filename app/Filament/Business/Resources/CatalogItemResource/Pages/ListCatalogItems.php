<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\CatalogItemResource\Pages;

use App\Filament\Business\Resources\CatalogItemResource;
use Filament\Resources\Pages\ListRecords;

class ListCatalogItems extends ListRecords
{
    protected static string $resource = CatalogItemResource::class;
}
