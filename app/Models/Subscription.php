<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use BelongsToTenant;
    use HasFactory;
    
    protected $table = 'subscriptions';

    protected $fillable = ['tenant_id','customer_id','plan_name','billing_cycle','allowance','allowance_unit','used','rollover','status','starts_at','renews_at','paused_at','price'];

    protected function casts(): array
    {
        return [
            'rollover' => 'boolean',
            'starts_at' => 'datetime',
            'renews_at' => 'datetime',
            'paused_at' => 'datetime',
            'price' => 'integer',
            'allowance' => 'integer',
            'used' => 'integer',
        ];
    }

}
