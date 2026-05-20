<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Notifications\InvoiceGeneratedNotification;
// use App\Models\Invoice; // Note: Model generated in next step
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Enums\InvoiceStatus;
use App\Models\Client;
use App\Http\Requests\Tenant\StoreInvoiceRequest;
use Illuminate\Http\RedirectResponse;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('invoices')
                ->join('clients', 'invoices.client_id', '=', 'clients.id')
                ->where('invoices.tenant_id', session('tenant_id'))
                ->select('invoices.*', 'clients.name as client_name', 'clients.email as client_email');

            return \Yajra\DataTables\Facades\DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $viewUrl = route('app.invoices.show', $row->id);
                    $sendUrl = route('app.invoices.send', $row->id);
                    return '<div class="flex items-center justify-end space-x-1">'.
                           '<a href="'.$viewUrl.'" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="View Invoice"><i class="fas fa-eye text-sm"></i></a>'.
                           '<a href="'.$sendUrl.'" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Send Email"><i class="fas fa-envelope text-sm"></i></a>'.
                           '</div>';
                })
                ->editColumn('total', function($row){
                    return '₹' . number_format((float)$row->total, 2);
                })
                ->editColumn('status', function($row){
                    $class = $row->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : ($row->status === 'unpaid' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800');
                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium '.$class.'">'.ucfirst($row->status).'</span>';
                })
                ->editColumn('due_date', function($row){
                    return \Carbon\Carbon::parse($row->due_date)->format('M d, Y');
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('app.invoices.index');
    }

    public function create(): View
    {
        $clients = Client::orderBy('name')->get();
        return view('app.invoices.create', compact('clients'));
    }

    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Generate invoice number
        $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad((string)(DB::table('invoices')->where('tenant_id', session('tenant_id'))->count() + 1), 4, '0', STR_PAD_LEFT);

        // Calculate totals
        $subtotal = 0;
        $items = [];

        foreach ($data['items'] as $item) {
            $lineTotal = $item['quantity'] * $item['unit_price'];
            $subtotal += $lineTotal;
            $items[] = [
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $lineTotal,
            ];
        }

        $taxRate = $data['tax_rate'] ?? 0;
        $taxTotal = $subtotal * ($taxRate / 100);
        $total = $subtotal + $taxTotal;

        // Create invoice using Eloquent for notification support
        $invoice = Invoice::create([
            'tenant_id' => session('tenant_id'),
            'client_id' => $data['client_id'],
            'invoice_number' => $invoiceNumber,
            'issue_date' => $data['issue_date'],
            'due_date' => $data['due_date'],
            'subtotal' => $subtotal,
            'tax_total' => $taxTotal,
            'total' => $total,
            'amount_paid' => 0,
            'status' => \App\Enums\InvoiceStatus::UNPAID,
        ]);

        // Create invoice items
        foreach ($items as $item) {
            $invoice->items()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['total'],
            ]);
        }

        // Notify client if they have an email
        if ($invoice->client && $invoice->client->email) {
            try {
                $invoice->client->notify(new InvoiceGeneratedNotification($invoice));
            } catch (\Exception $e) {
                // Log the error but don't stop the process
                \Illuminate\Support\Facades\Log::error('Invoice Mail Error: ' . $e->getMessage());
            }
        }

        return redirect()->route('app.invoices.index')->with('success', 'Invoice created and email sent successfully.');
    }

    public function show(int $id): View
    {
        // Typically would use Eloquent, using Query Builder for speed in SaaS
        $invoice = DB::table('invoices')->where('id', $id)->where('tenant_id', session('tenant_id'))->first();
        abort_if(!$invoice, 404);

        $client = DB::table('clients')->where('id', $invoice->client_id)->first();
        $items = DB::table('invoice_items')->where('invoice_id', $invoice->id)->get();
        $payments = DB::table('payments')->where('invoice_id', $invoice->id)->get();

        $tenantId = session('tenant_id');
        $tenant = DB::table('tenants')->where('id', $tenantId)->first();
        $companySettings = DB::table('settings')
            ->where('tenant_id', $tenantId)
            ->where('key', 'company_settings')
            ->first();
        $company = $companySettings ? json_decode($companySettings->value, true) : [];

        return view('app.invoices.show', compact('invoice', 'client', 'items', 'payments', 'company', 'tenant'));
    }

    public function downloadPdf(int $id)
    {
        $invoice = DB::table('invoices')->where('id', $id)->where('tenant_id', session('tenant_id'))->first();
        abort_if(!$invoice, 404);

        $client = DB::table('clients')->where('id', $invoice->client_id)->first();
        $items = DB::table('invoice_items')->where('invoice_id', $invoice->id)->get();
        
        $tenantId = session('tenant_id');
        $tenant = DB::table('tenants')->where('id', $tenantId)->first();
        $companySettings = DB::table('settings')
            ->where('tenant_id', $tenantId)
            ->where('key', 'company_settings')
            ->first();
        $company = $companySettings ? json_decode($companySettings->value, true) : [];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('app.invoices.pdf', compact('invoice', 'client', 'items', 'company', 'tenant'));
        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }

    public function sendMail(int $id): RedirectResponse
    {
        $invoice = Invoice::with('client')->where('id', $id)->where('tenant_id', session('tenant_id'))->first();
        abort_if(!$invoice, 404);

        if ($invoice->client && $invoice->client->email) {
            try {
                $invoice->client->notify(new InvoiceGeneratedNotification($invoice));
                return back()->with('success', 'Invoice email sent successfully to ' . $invoice->client->email);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Invoice Resend Mail Error: ' . $e->getMessage());
                return back()->with('error', 'Failed to send email: ' . $e->getMessage());
            }
        }

        return back()->with('error', 'Client does not have a valid email address.');
    }
}
