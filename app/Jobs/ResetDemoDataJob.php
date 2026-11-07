<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Artisan;

class ResetDemoDataJob implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        if (! config('app.demo_mode', env('DEMO_MODE', false))) {
            return;
        }

        Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
    }
}
