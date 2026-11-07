<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use BelongsToTenant;
    use HasFactory;
    
    protected $table = 'coupons';

    protected $fillable = ['tenant_id','code','type','value','min_spend','starts_at','ends_at','usage_limit','used_count','constraints','is_active'];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'constraints' => 'array',
            'is_active' => 'boolean',
        ];
    }

}
