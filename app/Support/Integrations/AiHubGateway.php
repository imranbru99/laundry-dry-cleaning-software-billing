<?php

declare(strict_types=1);

namespace App\Support\Integrations;

use App\Models\AiOutput;
use App\Models\Tenant;
use ImranDevBd\AiHub\Facades\AIHub;

final class AiHubGateway
{
    public function prompt(Tenant $tenant, string $feature, string $prompt, array $input = []): AiOutput
    {
        $settings = $tenant->settings['ai'] ?? [];
        $provider = $settings['provider'] ?? config('ai-hub.default', 'gemini');
        $model = $settings['model'] ?? null;

        $pending = $model
            ? AIHub::model($model)->prompt($prompt)
            : AIHub::prompt($prompt);

        $response = $pending->send();
        $text = is_object($response) && method_exists($response, 'text')
            ? $response->text()
            : (string) $response;

        return AiOutput::query()->create([
            'tenant_id' => $tenant->id,
            'user_id' => auth()->id(),
            'feature' => $feature,
            'provider' => $provider,
            'model' => $model,
            'input' => array_merge($input, ['prompt' => $prompt]),
            'output' => ['text' => $text],
            'status' => 'pending_review',
            'accepted' => false,
        ]);
    }

    public function accept(AiOutput $output): AiOutput
    {
        $output->update(['accepted' => true, 'status' => 'accepted']);

        return $output->fresh();
    }
}