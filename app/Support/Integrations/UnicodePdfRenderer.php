<?php

declare(strict_types=1);

namespace App\Support\Integrations;

use App\Models\Tenant;
use ImranDev\UnicodePdf\Facades\UnicodePdf;
use Symfony\Component\HttpFoundation\Response;

final class UnicodePdfRenderer
{
    private const PRESETS = [
        'bn' => 'bengali',
        'ar' => 'arabic',
        'hi' => 'hindi',
        'en' => 'universal',
        'es' => 'universal',
    ];

    public function download(Tenant $tenant, string $view, array $data, string $filename): Response
    {
        $locale = $tenant->locale ?? 'en';
        $preset = self::PRESETS[substr($locale, 0, 2)] ?? 'universal';

        return UnicodePdf::preset($preset)
            ->locale($locale)
            ->loadView($view, $data)
            ->a4()
            ->download($filename);
    }
}