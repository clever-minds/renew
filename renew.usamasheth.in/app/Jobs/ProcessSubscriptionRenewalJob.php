<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\ClientSubscription;
use App\Services\Renewals\RenewalService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessSubscriptionRenewalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public function __construct(public ClientSubscription $subscription)
    {
    }

    public function handle(RenewalService $renewalService): void
    {
        // The service handles transactions and updating the due dates safely
        $renewalService->process($this->subscription);
    }
}
