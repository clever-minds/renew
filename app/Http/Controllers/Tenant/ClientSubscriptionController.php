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
    public function index(Request $request): View
    {
        $query = ClientSubscription::with(['client', 'service']);

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $subscriptions = $query->orderBy('next_due_date', 'asc')->paginate(15)->withQueryString();

        return view('app.subscriptions.index', compact('subscriptions'));
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

        return back()->with('success', 'Subscription assigned successfully.');
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
