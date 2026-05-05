<?php

declare(strict_types=1);

namespace App\Services\Renewals;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Jobs\SendReminderJob;

class ReminderEngineService
{
    /**
     * Evaluates reminders for a specific date (usually today).
     * Dispatches jobs if idempotency check passes.
     */
    public function evaluateAndDispatch(): void
    {
        // 1. Get all active reminder rules for all active tenants
        $rules = DB::table('reminders')
            ->join('tenants', 'reminders.tenant_id', '=', 'tenants.id')
            ->where('reminders.is_active', true)
            ->where('tenants.status', 'active')
            ->select('reminders.*')
            ->get();

        foreach ($rules as $rule) {
            $targetDate = Carbon::now()->addDays($rule->days_offset)->toDateString();

            // 2. Find subscriptions matching this rule's target date
            // We use a LEFT JOIN on reminder_logs to check idempotency in the query itself.
            $subscriptions = DB::table('client_subscriptions')
                ->select('client_subscriptions.id', 'client_subscriptions.tenant_id')
                ->leftJoin('reminder_logs', function ($join) use ($rule) {
                    $join->on('client_subscriptions.id', '=', 'reminder_logs.client_subscription_id')
                         ->where('reminder_logs.reminder_id', '=', $rule->id)
                         ->whereIn('reminder_logs.status', ['sent', 'pending']);
                })
                ->where('client_subscriptions.tenant_id', $rule->tenant_id)
                ->where('client_subscriptions.status', 'active')
                ->where('client_subscriptions.next_due_date', $targetDate)
                ->whereNull('reminder_logs.id') // Idempotency check: log doesn't exist
                ->get();

            // 3. Dispatch jobs
            foreach ($subscriptions as $sub) {
                // Pre-emptively create the PENDING log to ensure idempotency even before the job runs
                $logId = DB::table('reminder_logs')->insertGetId([
                    'tenant_id' => $rule->tenant_id,
                    'client_subscription_id' => $sub->id,
                    'reminder_id' => $rule->id,
                    'status' => 'pending',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                // Dispatch the job
                SendReminderJob::dispatch((int) $logId, (int) $rule->id, (int) $sub->id);
            }
        }
    }
}
