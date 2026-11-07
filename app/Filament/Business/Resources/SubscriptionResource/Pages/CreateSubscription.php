<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\SubscriptionResource\Pages;

use App\Filament\Business\Resources\SubscriptionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSubscription extends CreateRecord
{
    protected static string $resource = SubscriptionResource::class;
}
