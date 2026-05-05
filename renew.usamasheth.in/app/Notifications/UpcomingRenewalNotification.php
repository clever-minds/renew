<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\ClientSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class UpcomingRenewalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private ClientSubscription $subscription,
        private object $rule
    ) {
    }

    public function via(object $notifiable): array
    {
        // $notifiable is usually the Client model
        $channels = [];
        
        if ($this->rule->type === 'email' || $this->rule->type === 'both') {
            $channels[] = 'mail';
        }

        // Future: add 'whatsapp' channel
        
        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        // Load the template from the database
        $template = DB::table('email_templates')
            ->where('tenant_id', $this->subscription->tenant_id)
            ->where('id', $this->rule->template_id)
            ->first();

        // Default fallback if no custom template exists
        $subject = $template->subject ?? 'Upcoming Subscription Renewal';
        $body = $template->body ?? "Your subscription for {$this->subscription->service->name} is due on {$this->subscription->next_due_date}.";

        // Simple variable replacement
        $body = str_replace('{client.name}', $notifiable->name, $body);
        $body = str_replace('{service.name}', $this->subscription->service->name, $body);
        $body = str_replace('{due_date}', $this->subscription->next_due_date, $body);
        $body = str_replace('{amount}', number_format((float)$this->subscription->price, 2), $body);

        return (new MailMessage)
            ->subject($subject)
            ->line($body)
            ->action('View Account', url('/'));
    }
}
