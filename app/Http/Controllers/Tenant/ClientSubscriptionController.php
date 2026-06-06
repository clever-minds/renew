<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\ClientSubscription;
use App\Models\Client;
use App\Models\Service;
use App\Http\Requests\Tenant\StoreSubscriptionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Enums\BillingCycle;
use App\Enums\SubscriptionStatus;
use App\Services\Billing\InvoiceService;
use App\Models\Payment;
use App\Enums\InvoiceStatus;

class ClientSubscriptionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ClientSubscription::with(['client', 'service'])->select('client_subscriptions.*');
            return \Yajra\DataTables\Facades\DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('client_name', function($row){
                    return $row->client->name;
                })
                ->addColumn('service_name', function($row){
                    return $row->service->name;
                })
                ->addColumn('action', function($row){
                    $viewUrl = route('app.subscriptions.show', $row->id);
                    return '<a href="'.$viewUrl.'" class="inline-flex items-center px-3 py-1 bg-white border border-gray-200 text-indigo-600 hover:bg-indigo-50 rounded-lg text-xs font-bold transition-colors">Manage</a>';
                })
                ->editColumn('price', function($row){
                    return '₹' . number_format((float)$row->price, 2) . ' /' . $row->service->billing_cycle->value;
                })
                ->editColumn('status', function($row){
                    $class = $row->status->value === 'active' ? 'bg-emerald-100 text-emerald-800' : ($row->status->value === 'overdue' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800');
                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium '.$class.'">'.ucfirst($row->status->value).'</span>';
                })
                ->editColumn('next_due_date', function($row){
                    return $row->next_due_date ? $row->next_due_date->format('M d, Y') : 'N/A';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('app.subscriptions.index');
    }

    public function create(Request $request): View
    {
        $clients = Client::orderBy('name')->get();
        $services = Service::where('is_active', true)->orderBy('name')->get();
        $selectedClient = null;

        if ($request->filled('client')) {
            $selectedClient = Client::find($request->get('client'));
        }

        return view('app.subscriptions.create', compact('clients', 'services', 'selectedClient'));
    }

    public function show(ClientSubscription $subscription): View
    {
        $subscription->load(['client', 'service']);
        $clients = Client::orderBy('name')->get();
        $services = Service::where('is_active', true)->orderBy('name')->get();

        return view('app.subscriptions.show', compact('subscription', 'clients', 'services'));
    }

    public function store(StoreSubscriptionRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $service = Service::findOrFail($data['service_id']);
        
        $startDate = Carbon::parse($data['start_date']);
        
        // Calculate initial due date
        $nextDueDate = match ($service->billing_cycle) {
            BillingCycle::MONTHLY => $startDate->copy()->addMonth(),
            BillingCycle::QUARTERLY => $startDate->copy()->addMonths(3),
            BillingCycle::SEMI_ANNUALLY => $startDate->copy()->addMonths(6),
            BillingCycle::ANNUALLY => $startDate->copy()->addYear(),
            BillingCycle::ONE_TIME => null,
        };

        ClientSubscription::create([
            'client_id' => $data['client_id'],
            'service_id' => $service->id,
            'price' => $data['price'], // Allows override
            'start_date' => $startDate->toDateString(),
            'next_due_date' => $nextDueDate?->toDateString(),
            'status' => SubscriptionStatus::ACTIVE->value,
            'auto_invoice' => $data['auto_invoice'] ?? true,
        ]);

        return redirect()->route('app.subscriptions.index')->with('success', 'Subscription assigned successfully.');
    }

    public function edit(ClientSubscription $subscription): RedirectResponse
    {
        return redirect()->route('app.subscriptions.show', $subscription);
    }

    public function update(StoreSubscriptionRequest $request, ClientSubscription $subscription): RedirectResponse
    {
        $data = $request->validated();
        $service = Service::findOrFail($data['service_id']);
        $startDate = Carbon::parse($data['start_date']);

        $nextDueDate = match ($service->billing_cycle) {
            BillingCycle::MONTHLY => $startDate->copy()->addMonth(),
            BillingCycle::QUARTERLY => $startDate->copy()->addMonths(3),
            BillingCycle::SEMI_ANNUALLY => $startDate->copy()->addMonths(6),
            BillingCycle::ANNUALLY => $startDate->copy()->addYear(),
            BillingCycle::ONE_TIME => null,
        };

        $subscription->update([
            'client_id' => $data['client_id'],
            'service_id' => $service->id,
            'price' => $data['price'],
            'start_date' => $startDate->toDateString(),
            'next_due_date' => $nextDueDate?->toDateString(),
            'auto_invoice' => $data['auto_invoice'] ?? true,
        ]);

        return redirect()->route('app.subscriptions.index')->with('success', 'Subscription updated successfully.');
    }

    public function destroy(ClientSubscription $subscription): RedirectResponse
    {
        $subscription->delete();

        return redirect()->route('app.subscriptions.index')->with('success', 'Subscription removed successfully.');
    }

    public function suspend(ClientSubscription $subscription): RedirectResponse
    {
        $subscription->update(['status' => SubscriptionStatus::SUSPENDED->value]);
        return back()->with('warning', 'Subscription suspended.');
    }

    public function activate(ClientSubscription $subscription): RedirectResponse
    {
        $subscription->update(['status' => SubscriptionStatus::ACTIVE->value]);
        return back()->with('success', 'Subscription reactivated.');
    }

    public function generateInvoice(ClientSubscription $subscription, InvoiceService $invoiceService): RedirectResponse
    {
        if ($subscription->status !== SubscriptionStatus::ACTIVE) {
            return back()->with('error', 'Cannot generate invoice for inactive subscription.');
        }

        try {
            $invoice = $invoiceService->generateFromSubscription($subscription);
            return redirect()->route('app.invoices.show', $invoice)->with('success', 'Invoice generated successfully from subscription.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to generate invoice: ' . $e->getMessage());
        }
    }

    public function recordPayment(Request $request, ClientSubscription $subscription, InvoiceService $invoiceService): RedirectResponse
    {
        if ($subscription->status !== SubscriptionStatus::ACTIVE) {
            return back()->with('error', 'Cannot record payment for inactive subscription.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'transaction_reference' => 'nullable|string|max:255',
        ]);

        try {
            // First generate the invoice
            $invoice = $invoiceService->generateFromSubscription($subscription);
            
            // Then record the payment against it
            Payment::create([
                'tenant_id' => session('tenant_id'),
                'invoice_id' => $invoice->id,
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'transaction_reference' => $validated['transaction_reference'],
                'payment_date' => now(),
            ]);

            $invoice->amount_paid += $validated['amount'];
            
            if ($invoice->amount_paid >= $invoice->total) {
                $invoice->status = InvoiceStatus::PAID;
            } elseif ($invoice->amount_paid > 0) {
                $invoice->status = InvoiceStatus::PARTIAL;
            }
            
            $invoice->save();

            return back()->with('success', 'Invoice generated and payment recorded successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to process payment: ' . $e->getMessage());
        }
    }
}
