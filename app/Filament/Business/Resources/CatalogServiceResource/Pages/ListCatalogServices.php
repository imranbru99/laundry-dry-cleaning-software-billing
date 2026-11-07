<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\CatalogServiceResource\Pages;

use App\Filament\Business\Resources\CatalogServiceResource;
use Filament\Resources\Pages\ListRecords;

class ListCatalogServices extends ListRecords
{
    protected static string $resource = CatalogServiceResource::class;
}
