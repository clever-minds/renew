<?php

declare(strict_types=1);

namespace App\Services\SaaS;

use App\Models\Tenant;
use App\Enums\TenantStatus;
use Carbon\Carbon;

class TenantSubscriptionService
{
    /**
     * Checks if the tenant has a valid trial or active SaaS subscription.
     */
    public function isValid(Tenant $tenant): boolean
    {
        if ($tenant->status === TenantStatus::ACTIVE) {
            return true;
        }

        if ($tenant->status === TenantStatus::TRIAL) {
            if ($tenant->trial_ends_at && $tenant->trial_ends_at->isFuture()) {
                return true;
            }
            
            // Trial expired
            $tenant->update(['status' => TenantStatus::SUSPENDED->value]);
            return false;
        }

        return false;
    }

    public function suspend(Tenant $tenant): void
    {
        $tenant->update(['status' => TenantStatus::SUSPENDED->value]);
    }
}
