<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources\InvoiceResource\Pages;

use App\Filament\Business\Resources\InvoiceResource;
use Filament\Resources\Pages\ListRecords;

class ListInvoices extends ListRecords
{
    protected static string $resource = InvoiceResource::class;
}
