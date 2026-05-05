<?php

declare(strict_types=1);

namespace App\Services\Billing;

use App\Models\ClientSubscription;
use App\Models\Invoice; // Note: Model assumed to be created later
use App\Models\InvoiceItem; // Note: Model assumed to be created later
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
            // We use standard DB inserts or model creation. Using generic Model array here.
            $invoiceId = DB::table('invoices')->insertGetId([
                'tenant_id' => $tenantId,
                'client_id' => $subscription->client_id,
                'invoice_number' => $invoiceNumber,
                'issue_date' => Carbon::now()->toDateString(),
                'due_date' => Carbon::now()->addDays(7)->toDateString(), // Default 7 days terms
                'subtotal' => $subscription->price,
                'tax_total' => 0.00, // Simplification for MVP
                'total' => $subscription->price,
                'amount_paid' => 0.00,
                'status' => InvoiceStatus::DRAFT->value,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Attach Line Item
            DB::table('invoice_items')->insert([
                'invoice_id' => $invoiceId,
                'description' => 'Renewal for: ' . $subscription->service->name,
                'quantity' => 1,
                'unit_price' => $subscription->price,
                'total' => $subscription->price,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Assuming we have an Invoice model to return, returning the raw record for now
            // In a real scenario with the Invoice model created, we'd do Invoice::find($invoiceId)
            return new Invoice(['id' => $invoiceId]); 
        });
    }
}
