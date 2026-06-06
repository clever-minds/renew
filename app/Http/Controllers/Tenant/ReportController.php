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

        if ($request->has('export') && $request->export === 'csv') {
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=revenue_report_{$startDate}_to_{$endDate}.csv",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];
            $callback = function() use($payments) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Date', 'Client', 'Invoice #', 'Payment Method', 'Amount']);
                foreach ($payments as $payment) {
                    fputcsv($file, [
                        \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d'),
                        $payment->client_name,
                        $payment->invoice_number,
                        ucwords(str_replace('_', ' ', $payment->payment_method)),
                        $payment->amount
                    ]);
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }

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
