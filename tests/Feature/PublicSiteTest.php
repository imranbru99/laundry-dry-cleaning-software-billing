<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_and_features_pages_render(): void
    {
        $this->seed();

        $this->get('/')->assertOk();
        $this->get('/features')->assertOk();
        $this->get('/pricing')->assertOk();
        $this->get('/demo')->assertOk();
        $this->get('/services')->assertOk();
        $this->get('/track')->assertOk();
    }
}
