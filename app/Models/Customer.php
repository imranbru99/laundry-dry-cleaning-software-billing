<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'customers';

    protected $fillable = ['tenant_id','user_id','type','name','email','phone','company_name','preferences','tags','lifetime_value','is_blacklisted','birthday','anniversary','notes'];

    protected function casts(): array
    {
        return [
            'preferences' => 'array',
            'tags' => 'array',
            'is_blacklisted' => 'boolean',
            'birthday' => 'date',
            'anniversary' => 'date',
            'lifetime_value' => 'integer',
        ];
    }

}
