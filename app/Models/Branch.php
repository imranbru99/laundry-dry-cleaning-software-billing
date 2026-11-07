<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use ImranDev\UniversalSlug\SlugOptions;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

class Branch extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUniversalSlug;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id', 'name', 'slug', 'code', 'type', 'email', 'phone',
        'address_line1', 'address_line2', 'city', 'state', 'postal_code', 'country',
        'latitude', 'longitude', 'timezone', 'opening_hours', 'is_central_plant',
        'is_active', 'settings',
    ];

    protected function casts(): array
    {
        return [
            'opening_hours' => 'array',
            'settings' => 'array',
            'is_central_plant' => 'boolean',
            'is_active' => 'boolean',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->uniqueScope(fn ($q) => $q->where('tenant_id', $this->tenant_id));
    }
}