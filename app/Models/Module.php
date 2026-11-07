<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'key',
        'name',
        'description',
        'dependencies',
        'permissions',
        'settings_schema',
        'menu',
        'is_core',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'dependencies' => 'array',
            'permissions' => 'array',
            'settings_schema' => 'array',
            'menu' => 'array',
            'is_core' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
