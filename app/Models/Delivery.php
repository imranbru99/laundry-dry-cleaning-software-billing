<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use BelongsToTenant;
    use HasFactory;
    
    protected $table = 'deliveries';

    protected $fillable = ['tenant_id','branch_id','customer_id','order_id','driver_id','number','status','scheduled_at','completed_at','address','latitude','longitude','otp','proof_photo','signature','cod_collected','meta'];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'completed_at' => 'datetime',
            'meta' => 'array',
            'cod_collected' => 'integer',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

}
