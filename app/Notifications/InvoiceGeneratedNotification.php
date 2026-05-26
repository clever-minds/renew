<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

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
        
        $tenantId = $this->invoice->tenant_id;
        $tenant = DB::table('tenants')->where('id', $tenantId)->first();
        $companySettings = DB::table('settings')
            ->where('tenant_id', $tenantId)
            ->where('key', 'company_settings')
            ->first();
        $company = $companySettings ? json_decode($companySettings->value, true) : [];

        $pdf = Pdf::loadView('app.invoices.pdf', [
            'invoice' => $this->invoice,
            'client' => $this->invoice->client,
            'items' => $this->invoice->items,
            'company' => $company,
            'tenant' => $tenant
        ]);

        return (new MailMessage)
            ->subject('New Invoice Generated: ' . $this->invoice->invoice_number)
            ->view('emails.invoices.generated', [
                'invoice' => $this->invoice,
                'client' => $this->invoice->client,
                'company' => $company,
                'tenant' => $tenant
            ])
            ->attachData($pdf->output(), "invoice-{$this->invoice->invoice_number}.pdf", [
                'mime' => 'application/pdf',
            ]);
    }
}
