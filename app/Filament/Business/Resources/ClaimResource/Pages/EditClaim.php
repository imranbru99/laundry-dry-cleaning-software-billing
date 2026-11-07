<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\ClaimResource\Pages;

use App\Filament\Business\Resources\ClaimResource;
use Filament\Resources\Pages\EditRecord;

class EditClaim extends EditRecord
{
    protected static string $resource = ClaimResource::class;
}
