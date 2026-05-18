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

class ClientSubscriptionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ClientSubscription::with(['client', 'service']);
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

    public function store(StoreSubscriptionRequest $request, \App\Services\Billing\InvoiceService $invoiceService): RedirectResponse
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

        $subscription = ClientSubscription::create([
            'client_id' => $data['client_id'],
            'service_id' => $service->id,
            'price' => $data['price'], // Allows override
            'start_date' => $startDate->toDateString(),
            'next_due_date' => $nextDueDate?->toDateString(),
            'status' => SubscriptionStatus::ACTIVE->value,
            'auto_invoice' => $request->boolean('auto_invoice'),
        ]);

        // Immediately generate the first invoice if auto-invoicing is enabled
        if ($subscription->auto_invoice) {
            try {
                $invoiceService->generateFromSubscription($subscription);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Auto-Invoice Generation on Subscription Creation Failed: ' . $e->getMessage());
            }
        }

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
            'auto_invoice' => $request->boolean('auto_invoice'),
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
}
