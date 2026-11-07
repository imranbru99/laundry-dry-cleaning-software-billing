<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use ImranDev\UniversalSlug\SlugOptions;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

class CatalogItem extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUniversalSlug;
    use SoftDeletes;

    protected $table = 'catalog_items';

    protected $fillable = ['tenant_id','service_category_id','name','name_i18n','slug','sku','description','image_path','unit','is_active'];

    protected function casts(): array
    {
        return [
            'name_i18n' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->uniqueScope(fn ($q) => $q->where('tenant_id', $this->tenant_id));
    }
}
