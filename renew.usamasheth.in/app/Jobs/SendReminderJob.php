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
            // Here we would use NotificationService to parse templates and send Mail/WhatsApp.
            // For example:
            // $subscription = ClientSubscription::with('client')->find($this->clientSubscriptionId);
            // $rule = DB::table('reminders')->find($this->reminderRuleId);
            // Notification::send($subscription->client, new SubscriptionReminder($subscription, $rule));

            // On success, mark the log as sent to satisfy idempotency
            DB::table('reminder_logs')->where('id', $this->reminderLogId)->update([
                'status' => 'sent',
                'sent_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

        } catch (\Exception $e) {
            // On failure, mark the log as failed so it can be manually retried if needed,
            // or the job's built-in retry mechanism will attempt it again.
            if ($this->attempts() >= $this->tries) {
                DB::table('reminder_logs')->where('id', $this->reminderLogId)->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'updated_at' => Carbon::now(),
                ]);
            }
            
            throw $e; // Re-throw to let the queue worker know it failed
        }
    }
}
