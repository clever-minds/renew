<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\Dashboard\DashboardCacheService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecalculateDashboardStatsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $tenantId)
    {
    }

    public function handle(DashboardCacheService $dashboardCacheService): void
    {
        $dashboardCacheService->recalculateForTenant($this->tenantId);
    }
}
