<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\CatalogServiceResource\Pages;

use App\Filament\Business\Resources\CatalogServiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCatalogService extends CreateRecord
{
    protected static string $resource = CatalogServiceResource::class;
}
