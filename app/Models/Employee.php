<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use BelongsToTenant;
    use HasFactory;
    
    protected $table = 'employees';

    protected $fillable = ['tenant_id','branch_id','user_id','employee_code','name','department','designation','joined_at','base_salary','salary_structure','is_active'];

    protected function casts(): array
    {
        return [
            'joined_at' => 'date',
            'salary_structure' => 'array',
            'is_active' => 'boolean',
            'base_salary' => 'integer',
        ];
    }

}
