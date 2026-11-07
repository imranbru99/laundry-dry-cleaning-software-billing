<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use BelongsToTenant;
    use HasFactory;
    
    protected $table = 'order_items';

    protected $fillable = ['tenant_id','order_id','catalog_item_id','catalog_service_id','name','quantity','unit_price','line_total','addons','notes'];

    protected function casts(): array
    {
        return [
            'addons' => 'array',
            'quantity' => 'integer',
            'unit_price' => 'integer',
            'line_total' => 'integer',
        ];
    }

}
