<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use BelongsToTenant;
    use HasFactory;
    
    protected $table = 'claims';

    protected $fillable = ['tenant_id','customer_id','order_id','garment_tag_id','type','status','compensation','description','meta'];

    protected function casts(): array
    {
        return [
            'compensation' => 'integer',
            'meta' => 'array',
        ];
    }

}
