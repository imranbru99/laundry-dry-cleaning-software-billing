<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    use BelongsToTenant;
    use HasFactory;
    
    protected $table = 'customer_addresses';

    protected $fillable = ['tenant_id','customer_id','label','line1','line2','city','state','postal_code','country','latitude','longitude','is_default'];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

}
