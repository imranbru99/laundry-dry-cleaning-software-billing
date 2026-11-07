<?php

declare(strict_types=1);

namespace App\Filament\Staff\Resources\OrderResource\Pages;

use App\Filament\Staff\Resources\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
}
