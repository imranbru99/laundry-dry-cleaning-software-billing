<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\ContractResource\Pages;

use App\Filament\Business\Resources\ContractResource;
use Filament\Resources\Pages\ListRecords;

class ListContracts extends ListRecords
{
    protected static string $resource = ContractResource::class;
}
