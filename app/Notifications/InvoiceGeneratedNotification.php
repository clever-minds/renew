<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceGeneratedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private Invoice $invoice)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        // Load relationships if not loaded
        $this->invoice->loadMissing(['client', 'items']);
        
        $pdf = Pdf::loadView('app.invoices.pdf', [
            'invoice' => $this->invoice,
            'client' => $this->invoice->client,
            'items' => $this->invoice->items
        ]);

        return (new MailMessage)
            ->subject('New Invoice Generated: ' . $this->invoice->invoice_number)
            ->line('A new invoice has been generated for your subscription.')
            ->line('Invoice Number: ' . $this->invoice->invoice_number)
            ->line('Amount Due: ₹' . number_format((float)$this->invoice->total, 2))
            ->line('Due Date: ' . $this->invoice->due_date->format('M d, Y'))
            ->attachData($pdf->output(), "invoice-{$this->invoice->invoice_number}.pdf", [
                'mime' => 'application/pdf',
            ])
            ->line('Thank you for using our service!');
    }
}
