<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\ContractResource\Pages;

use App\Filament\Business\Resources\ContractResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContract extends CreateRecord
{
    protected static string $resource = ContractResource::class;
}
