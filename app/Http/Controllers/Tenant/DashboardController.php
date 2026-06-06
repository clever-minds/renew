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

        // Calculate MRR dynamically
        $activeSubscriptions = DB::table('client_subscriptions')
            ->join('services', 'client_subscriptions.service_id', '=', 'services.id')
            ->where('client_subscriptions.tenant_id', $tenantId)
            ->where('client_subscriptions.status', 'active')
            ->select('client_subscriptions.price', 'services.billing_cycle')
            ->get();

        $mrr = 0.00;
        foreach ($activeSubscriptions as $sub) {
            $price = (float) $sub->price;
            switch ($sub->billing_cycle) {
                case 'monthly': $mrr += $price; break;
                case 'quarterly': $mrr += $price / 3; break;
                case 'semi_annually': $mrr += $price / 6; break;
                case 'annually': $mrr += $price / 12; break;
            }
        }

        // Calculate Overdue Amount
        $overdueAmount = DB::table('invoices')
            ->where('tenant_id', $tenantId)
            ->where('status', 'overdue')
            ->sum(DB::raw('total - amount_paid'));

        // Calculate Upcoming Renewals Count (next 30 days)
        $upcomingRenewalsCount = DB::table('client_subscriptions')
            ->where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->whereNotNull('next_due_date')
            ->where('next_due_date', '<=', now()->addDays(30))
            ->count();

        $stats = [
            'mrr' => $mrr,
            'overdue_amount' => (float) $overdueAmount,
            'upcoming_renewals_count' => $upcomingRenewalsCount
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
            ->select('client_subscriptions.*', 'client_subscriptions.id as id', 'clients.name as client_name', 'services.name as service_name')
            ->orderBy('next_due_date', 'asc')
            ->limit(5)
            ->get();

        return view('app.dashboard', compact('stats', 'recentPayments', 'upcomingRenewals'));
    }
}
