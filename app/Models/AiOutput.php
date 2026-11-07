<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiOutput extends Model
{
    use BelongsToTenant;
    use HasFactory;
    
    protected $table = 'ai_outputs';

    protected $fillable = ['tenant_id','user_id','feature','provider','model','input','output','status','accepted','tokens','cost_minor'];

    protected function casts(): array
    {
        return [
            'input' => 'array',
            'output' => 'array',
            'accepted' => 'boolean',
            'tokens' => 'integer',
            'cost_minor' => 'integer',
        ];
    }

}
