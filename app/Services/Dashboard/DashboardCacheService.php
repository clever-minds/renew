<?php

declare(strict_types=1);

namespace App\Services\Dashboard;

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardCacheService
{
    public function recalculateForTenant(int $tenantId): void
    {
        // Calculate MRR (Monthly Recurring Revenue)
        // A simple calculation: sum of active monthly subscriptions + (annual/12)
        $monthlySubTotal = DB::table('client_subscriptions')
            ->join('services', 'client_subscriptions.service_id', '=', 'services.id')
            ->where('client_subscriptions.tenant_id', $tenantId)
            ->where('client_subscriptions.status', 'active')
            ->where('services.billing_cycle', 'monthly')
            ->sum('client_subscriptions.price');
            
        $annualSubTotal = DB::table('client_subscriptions')
            ->join('services', 'client_subscriptions.service_id', '=', 'services.id')
            ->where('client_subscriptions.tenant_id', $tenantId)
            ->where('client_subscriptions.status', 'active')
            ->where('services.billing_cycle', 'annually')
            ->sum('client_subscriptions.price');
            
        $mrr = $monthlySubTotal + ($annualSubTotal / 12);

        // Overdue Invoices
        $overdueAmount = DB::table('invoices')
            ->where('tenant_id', $tenantId)
            ->where('status', 'overdue')
            ->sum(DB::raw('total - amount_paid'));

        // Upcoming Renewals (next 30 days)
        $upcomingCount = DB::table('client_subscriptions')
            ->where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->whereBetween('next_due_date', [Carbon::now()->toDateString(), Carbon::now()->addDays(30)->toDateString()])
            ->count();

        $stats = [
            'mrr' => round((float)$mrr, 2),
            'overdue_amount' => round((float)$overdueAmount, 2),
            'upcoming_renewals_count' => $upcomingCount,
            'calculated_at' => Carbon::now()->toIso8601String(),
        ];

        // Store in settings table (upsert)
        DB::table('settings')->updateOrInsert(
            ['tenant_id' => $tenantId, 'key' => 'dashboard_stats_cache'],
            ['value' => json_encode($stats)]
        );
    }
}
