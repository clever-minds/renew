<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $tenantId = session('tenant_id');

        // Load cached stats to prevent heavy sum() queries
        $statsRecord = DB::table('settings')
            ->where('tenant_id', $tenantId)
            ->where('key', 'dashboard_stats_cache')
            ->first();

        $stats = $statsRecord ? json_decode($statsRecord->value, true) : [
            'mrr' => 0.00,
            'overdue_amount' => 0.00,
            'upcoming_renewals_count' => 0
        ];

        // High-performance direct queries for recent lists
        $recentPayments = DB::table('payments')
            ->join('invoices', 'payments.invoice_id', '=', 'invoices.id')
            ->join('clients', 'invoices.client_id', '=', 'clients.id')
            ->where('payments.tenant_id', $tenantId)
            ->select('payments.*', 'invoices.invoice_number', 'clients.name as client_name')
            ->orderBy('payment_date', 'desc')
            ->limit(5)
            ->get();

        $upcomingRenewals = DB::table('client_subscriptions')
            ->join('clients', 'client_subscriptions.client_id', '=', 'clients.id')
            ->join('services', 'client_subscriptions.service_id', '=', 'services.id')
            ->where('client_subscriptions.tenant_id', $tenantId)
            ->where('client_subscriptions.status', 'active')
            ->select('client_subscriptions.*', 'clients.name as client_name', 'services.name as service_name')
            ->orderBy('next_due_date', 'asc')
            ->limit(5)
            ->get();

        return view('app.dashboard', compact('stats', 'recentPayments', 'upcomingRenewals'));
    }
}
