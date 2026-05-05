<?php

declare(strict_types=1);

namespace App\Services\Renewals;

use App\Models\ClientSubscription;
use App\Services\Billing\InvoiceService;
use App\Enums\BillingCycle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RenewalService
{
    public function __construct(private InvoiceService $invoiceService)
    {
    }

    public function process(ClientSubscription $subscription): void
    {
        DB::transaction(function () use ($subscription) {
            // 1. Generate Invoice if auto_invoice is enabled
            if ($subscription->auto_invoice) {
                $this->invoiceService->generateFromSubscription($subscription);
            }

            // 2. Calculate next due date
            $currentDueDate = Carbon::parse($subscription->next_due_date ?? Carbon::now());
            $nextDueDate = match ($subscription->service->billing_cycle) {
                BillingCycle::MONTHLY => $currentDueDate->copy()->addMonth(),
                BillingCycle::QUARTERLY => $currentDueDate->copy()->addMonths(3),
                BillingCycle::SEMI_ANNUALLY => $currentDueDate->copy()->addMonths(6),
                BillingCycle::ANNUALLY => $currentDueDate->copy()->addYear(),
                BillingCycle::ONE_TIME => null,
            };

            // 3. Update subscription
            if ($nextDueDate) {
                $subscription->update([
                    'next_due_date' => $nextDueDate->toDateString(),
                ]);
            } else {
                // One-time services don't renew
                $subscription->update([
                    'status' => 'expired'
                ]);
            }
        });
    }
}
