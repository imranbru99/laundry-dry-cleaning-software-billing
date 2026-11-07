<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\PaymentResource\Pages;

use App\Filament\Business\Resources\PaymentResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;
}
