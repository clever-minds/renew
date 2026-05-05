<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
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
    public function index(Request $request): View
    {
        // Thin controller eager loading
        $query = DB::table('invoices')
            ->join('clients', 'invoices.client_id', '=', 'clients.id')
            ->where('invoices.tenant_id', session('tenant_id'))
            ->select('invoices.*', 'clients.name as client_name', 'clients.email as client_email');

        if ($request->filled('status')) {
            $query->where('invoices.status', $request->get('status'));
        }

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('invoices.invoice_number', 'like', "%{$search}%")
                  ->orWhere('clients.name', 'like', "%{$search}%");
            });
        }

        $invoices = $query->orderBy('invoices.issue_date', 'desc')->paginate(15);

        // Required to maintain pagination state when using Query Builder directly
        $invoices->appends($request->all());

        return view('app.invoices.index', compact('invoices'));
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
        $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad((DB::table('invoices')->where('tenant_id', session('tenant_id'))->count() + 1), 4, '0', STR_PAD_LEFT);

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

        // Create invoice
        $invoiceId = DB::table('invoices')->insertGetId([
            'tenant_id' => session('tenant_id'),
            'client_id' => $data['client_id'],
            'invoice_number' => $invoiceNumber,
            'issue_date' => $data['issue_date'],
            'due_date' => $data['due_date'],
            'subtotal' => $subtotal,
            'tax_total' => $taxTotal,
            'total' => $total,
            'amount_paid' => 0,
            'status' => 'unpaid',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create invoice items
        foreach ($items as $item) {
            DB::table('invoice_items')->insert([
                'invoice_id' => $invoiceId,
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['total'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('app.invoices.show', $invoiceId)->with('success', 'Invoice created successfully.');
    }

    public function show(int $id): View
    {
        // Typically would use Eloquent, using Query Builder for speed in SaaS
        $invoice = DB::table('invoices')->where('id', $id)->where('tenant_id', session('tenant_id'))->first();
        abort_if(!$invoice, 404);

        $client = DB::table('clients')->where('id', $invoice->client_id)->first();
        $items = DB::table('invoice_items')->where('invoice_id', $invoice->id)->get();
        $payments = DB::table('payments')->where('invoice_id', $invoice->id)->get();

        return view('app.invoices.show', compact('invoice', 'client', 'items', 'payments'));
    }
}
