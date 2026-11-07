<?php

declare(strict_types=1);

namespace App\Support\Integrations;

use App\Models\Employee;
use App\Models\Tenant;
use ImranDevBd\AttendanceHub\Facades\AttendanceHub;
use Illuminate\Support\Collection;

final class AttendanceHubBridge
{
    public function syncDevice(Tenant $tenant, int|string $deviceId): Collection
    {
        return AttendanceHub::sync($deviceId);
    }

    public function simulateDay(Tenant $tenant): Collection
    {
        AttendanceHub::fake();

        return collect(Employee::query()->where('tenant_id', $tenant->id)->get())
            ->map(fn (Employee $employee) => [
                'employee_code' => $employee->employee_code,
                'punched_at' => now()->setTime(9, 0),
                'type' => 'check_in',
            ]);
    }
}