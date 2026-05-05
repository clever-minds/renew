<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\ClientSubscription;
use App\Jobs\ProcessSubscriptionRenewalJob;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessRenewalsCommand extends Command
{
    protected $signature = 'renewals:process';
    protected $description = 'Process subscriptions that are due for renewal and generate invoices.';

    public function handle(): int
    {
        $this->info('Starting renewal processing...');

        // Find all active subscriptions where next_due_date is today or in the past
        $subscriptions = ClientSubscription::where('status', 'active')
            ->whereDate('next_due_date', '<=', Carbon::now()->toDateString())
            ->get();

        $count = 0;
        foreach ($subscriptions as $subscription) {
            ProcessSubscriptionRenewalJob::dispatch($subscription);
            $count++;
        }

        $this->info("Dispatched {$count} subscription renewal jobs.");

        return Command::SUCCESS;
    }
}
