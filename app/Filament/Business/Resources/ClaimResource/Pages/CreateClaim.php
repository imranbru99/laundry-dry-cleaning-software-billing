<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\ClaimResource\Pages;

use App\Filament\Business\Resources\ClaimResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClaim extends CreateRecord
{
    protected static string $resource = ClaimResource::class;
}
