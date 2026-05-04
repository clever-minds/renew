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

    public function index(Request $request): View
    {
        // High-performance Query Builder for shared hosting
        $query = DB::table('payments')
            ->join('invoices', 'payments.invoice_id', '=', 'invoices.id')
            ->join('clients', 'invoices.client_id', '=', 'clients.id')
            ->where('payments.tenant_id', session('tenant_id'))
            ->select(
                'payments.*',
                'invoices.invoice_number',
                'clients.name as client_name'
            );

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('payments.transaction_reference', 'like', "%{$search}%")
                  ->orWhere('invoices.invoice_number', 'like', "%{$search}%")
                  ->orWhere('clients.name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('method')) {
            $query->where('payments.payment_method', $request->get('method'));
        }

        $payments = $query->orderBy('payments.payment_date', 'desc')->paginate(15)->withQueryString();

        return view('app.payments.index', compact('payments'));
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

            return back()->with('success', 'Payment logged successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
