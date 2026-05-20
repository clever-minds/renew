<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Services\Billing\PaymentService;
use App\Http\Requests\Tenant\StorePaymentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService)
    {
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('payments')
                ->join('invoices', 'payments.invoice_id', '=', 'invoices.id')
                ->join('clients', 'invoices.client_id', '=', 'clients.id')
                ->where('payments.tenant_id', session('tenant_id'))
                ->select(
                    'payments.*',
                    'invoices.invoice_number',
                    'clients.name as client_name'
                );

            return \Yajra\DataTables\Facades\DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('amount', function($row){
                    return '₹' . number_format((float)$row->amount, 2);
                })
                ->editColumn('payment_date', function($row){
                    return \Carbon\Carbon::parse($row->payment_date)->format('M d, Y');
                })
                ->editColumn('payment_method', function($row){
                    return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 uppercase">'.$row->payment_method.'</span>';
                })
                ->rawColumns(['payment_method'])
                ->make(true);
        }

        $invoices = DB::table('invoices')
            ->join('clients', 'invoices.client_id', '=', 'clients.id')
            ->where('invoices.tenant_id', session('tenant_id'))
            ->where('invoices.status', '!=', 'paid')
            ->select('invoices.id', 'invoices.invoice_number', 'invoices.total', 'invoices.amount_paid', 'clients.name as client_name')
            ->orderBy('invoices.invoice_number', 'desc')
            ->get();

        return view('app.payments.index', compact('invoices'));
    }

    public function store(StorePaymentRequest $request): RedirectResponse
    {
        $data = $request->validated();

        try {
            // Service handles transactions, invoice status updates, and overpayment checks
            $this->paymentService->process(
                (int) $data['invoice_id'],
                (float) $data['amount'],
                $data['payment_method'],
                $data['transaction_reference'] ?? null
            );

            return redirect()->route('app.payments.index')->with('success', 'Payment logged successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
