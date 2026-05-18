<?php

declare(strict_types=1);

namespace App\Services\Billing;

use App\Models\ClientSubscription;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Enums\InvoiceStatus;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class InvoiceService
{
    public function generateFromSubscription(ClientSubscription $subscription): Invoice
    {
        return DB::transaction(function () use ($subscription) {
            $tenantId = $subscription->tenant_id;
            
            // Generate a unique sequential invoice number per tenant
            $latestInvoice = DB::table('invoices')
                ->where('tenant_id', $tenantId)
                ->whereYear('created_at', Carbon::now()->year)
                ->lockForUpdate()
                ->orderBy('id', 'desc')
                ->first();

            $sequence = $latestInvoice ? intval(substr($latestInvoice->invoice_number, -4)) + 1 : 1;
            $invoiceNumber = 'INV-' . Carbon::now()->year . '-' . str_pad((string)$sequence, 4, '0', STR_PAD_LEFT);

            // Create Invoice Header
            $invoice = Invoice::create([
                'tenant_id' => $tenantId,
                'client_id' => $subscription->client_id,
                'invoice_number' => $invoiceNumber,
                'issue_date' => Carbon::now(),
                'due_date' => Carbon::now()->addDays(7), // Default 7 days terms
                'subtotal' => $subscription->price,
                'tax_total' => 0.00,
                'total' => $subscription->price,
                'amount_paid' => 0.00,
                'status' => InvoiceStatus::DRAFT,
            ]);

            // Attach Line Item
            $invoice->items()->create([
                'description' => 'Renewal for: ' . $subscription->service->name,
                'quantity' => 1,
                'unit_price' => $subscription->price,
                'total' => $subscription->price,
            ]);

            // Notify client if they have an email
            if ($subscription->client && $subscription->client->email) {
                $subscription->client->notify(new \App\Notifications\InvoiceGeneratedNotification($invoice));
            }

            return $invoice;
        });
    }
}
