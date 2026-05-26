<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function revenue(Request $request): View
    {
        $tenantId = session('tenant_id');
        
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Fast Query Builder aggregation
        $payments = DB::table('payments')
            ->join('invoices', 'payments.invoice_id', '=', 'invoices.id')
            ->join('clients', 'invoices.client_id', '=', 'clients.id')
            ->where('payments.tenant_id', $tenantId)
            ->whereBetween('payments.payment_date', [$startDate, $endDate])
            ->select('payments.*', 'invoices.invoice_number', 'clients.name as client_name')
            ->orderBy('payments.payment_date', 'desc')
            ->get();

        $totalRevenue = $payments->sum('amount');
        
        // Group by payment method for chart data
        $revenueByMethod = $payments->groupBy('payment_method')->map(function ($row) {
            return $row->sum('amount');
        });

        return view('app.reports.revenue', compact('payments', 'totalRevenue', 'revenueByMethod', 'startDate', 'endDate'));
    }

    public function subscriptions(Request $request): View
    {
        $tenantId = session('tenant_id');
        
        $status = $request->get('status', 'active');

        $subscriptions = DB::table('client_subscriptions')
            ->join('clients', 'client_subscriptions.client_id', '=', 'clients.id')
            ->join('services', 'client_subscriptions.service_id', '=', 'services.id')
            ->where('client_subscriptions.tenant_id', $tenantId)
            ->where('client_subscriptions.status', $status)
            ->select('client_subscriptions.*', 'client_subscriptions.id as id', 'clients.name as client_name', 'services.name as service_name', 'services.billing_cycle')
            ->orderBy('client_subscriptions.next_due_date', 'asc')
            ->paginate(20)
            ->withQueryString();

        return view('app.reports.subscriptions', compact('subscriptions', 'status'));
    }
}
