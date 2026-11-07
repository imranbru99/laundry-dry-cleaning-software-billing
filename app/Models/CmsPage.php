<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ImranDev\UniversalSlug\SlugOptions;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

class CmsPage extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUniversalSlug;

    protected $table = 'cms_pages';

    protected $fillable = ['tenant_id','title','title_i18n','slug','locale','template','sections','body','seo_title','seo_description','is_published','published_at'];

    protected function casts(): array
    {
        return [
            'title_i18n' => 'array',
            'sections' => 'array',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
}
