<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ImranDev\UniversalSlug\SlugOptions;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

class CatalogService extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUniversalSlug;

    protected $table = 'catalog_services';

    protected $fillable = ['tenant_id','name','name_i18n','slug','code','description','default_tat_hours','is_active'];

    protected function casts(): array
    {
        return [
            'name_i18n' => 'array',
            'is_active' => 'boolean',
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
