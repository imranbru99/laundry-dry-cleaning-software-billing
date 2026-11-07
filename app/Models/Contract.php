<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use BelongsToTenant;
    use HasFactory;
    
    protected $table = 'contracts';

    protected $fillable = ['tenant_id','customer_id','name','status','pricing','sla','starts_on','ends_on','credit_limit'];

    protected function casts(): array
    {
        return [
            'pricing' => 'array',
            'sla' => 'array',
            'starts_on' => 'date',
            'ends_on' => 'date',
            'credit_limit' => 'integer',
        ];
    }

}
