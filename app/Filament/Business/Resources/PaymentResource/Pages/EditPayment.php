<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\PaymentResource\Pages;

use App\Filament\Business\Resources\PaymentResource;
use Filament\Resources\Pages\EditRecord;

class EditPayment extends EditRecord
{
    protected static string $resource = PaymentResource::class;
}
