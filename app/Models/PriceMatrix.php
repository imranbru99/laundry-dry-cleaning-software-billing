<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceMatrix extends Model
{
    use BelongsToTenant;
    use HasFactory;
    
    protected $table = 'price_matrix';

    protected $fillable = ['tenant_id','branch_id','catalog_item_id','catalog_service_id','customer_id','segment','price','currency','valid_from','valid_to'];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'valid_from' => 'datetime',
            'valid_to' => 'datetime',
        ];
    }

}
