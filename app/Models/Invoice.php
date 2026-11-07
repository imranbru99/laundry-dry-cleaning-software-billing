<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'invoices';

    protected $fillable = ['tenant_id','branch_id','customer_id','order_id','number','type','status','subtotal','tax_total','discount_total','grand_total','amount_paid','currency','due_date','issued_at','line_items'];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'issued_at' => 'datetime',
            'line_items' => 'array',
            'subtotal' => 'integer',
            'tax_total' => 'integer',
            'discount_total' => 'integer',
            'grand_total' => 'integer',
            'amount_paid' => 'integer',
        ];
    }

}
