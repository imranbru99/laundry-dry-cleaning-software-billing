<?php

declare(strict_types=1);

namespace App\Support\Integrations;

use App\Models\CatalogItem;
use App\Models\CatalogService;
use App\Models\Tenant;
use ImranDevBd\AiCatalog\Facades\AiCatalog;
use ImranDev\UniversalSlug\Facades\UniversalSlug;

final class AiCatalogImporter
{
    public function importFromKeyword(Tenant $tenant, string $keyword): object
    {
        return AiCatalog::fromKeyword($keyword)->generate();
    }

    public function importFromUrl(Tenant $tenant, string $url): object
    {
        return AiCatalog::fromUrl($url)->generate();
    }

    public function publishApprovedDraft(Tenant $tenant, object $draft): CatalogItem
    {
        $data = method_exists($draft, 'toArray') ? $draft->toArray() : (array) $draft;
        $title = $data['title'] ?? $data['name'] ?? 'Imported item';

        return CatalogItem::query()->create([
            'tenant_id' => $tenant->id,
            'name' => $title,
            'slug' => UniversalSlug::generate($title),
            'description' => $data['description'] ?? null,
            'unit' => 'piece',
            'is_active' => false, // staging until human review
        ]);
    }
}