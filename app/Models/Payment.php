<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use BelongsToTenant;
    use HasFactory;
    
    protected $table = 'payments';

    protected $fillable = ['tenant_id','invoice_id','order_id','customer_id','method','status','amount','currency','gateway_reference','meta','received_by'];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'meta' => 'array',
        ];
    }

}
