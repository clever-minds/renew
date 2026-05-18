<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SendReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public function __construct(
        public int $reminderLogId,
        public int $reminderRuleId,
        public int $clientSubscriptionId
    ) {
    }

    public function handle(): void
    {
        try {
            $subscription = \App\Models\ClientSubscription::with(['client', 'service'])->find($this->clientSubscriptionId);
            if (!$subscription || !$subscription->client) {
                throw new \Exception("Subscription or Client not found.");
            }

            $rule = \Illuminate\Support\Facades\DB::table('reminders')->where('id', $this->reminderRuleId)->first();
            if (!$rule) {
                throw new \Exception("Reminder rule not found.");
            }

            // Send notification
            $subscription->client->notify(new \App\Notifications\UpcomingRenewalNotification($subscription, $rule));

            // On success, mark the log as sent
            DB::table('reminder_logs')->where('id', $this->reminderLogId)->update([
                'status' => 'sent',
                'sent_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

        } catch (\Exception $e) {
            if ($this->attempts() >= $this->tries) {
                DB::table('reminder_logs')->where('id', $this->reminderLogId)->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'updated_at' => Carbon::now(),
                ]);
            }
            
            throw $e;
        }
    }
}
