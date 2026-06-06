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

            $companySettings = DB::table('settings')->where('tenant_id', $tenantId)->where('key', 'company_settings')->first();
            $company = $companySettings ? json_decode($companySettings->value, true) : [];
            $hasCompanyGst = !empty($company['tax_number']);
            $hasClientGst = !empty($subscription->client->gst_number);

            $service = $subscription->service;
            $subtotal = $subscription->price;
            $taxRate = 0;
            $taxTotal = 0;
            $taxType = 'none';

            if ($hasCompanyGst && $hasClientGst && $service->tax_rate > 0) {
                $taxRate = $service->tax_rate;
                $taxTotal = ($subtotal * $taxRate) / 100;
                $taxType = 'cgst_sgst'; // Default guess; user can edit draft if needed
            }

            $total = $subtotal + $taxTotal;

            // Create Invoice Header
            $invoice = Invoice::create([
                'tenant_id' => $tenantId,
                'client_id' => $subscription->client_id,
                'invoice_number' => $invoiceNumber,
                'issue_date' => Carbon::now(),
                'due_date' => Carbon::now()->addDays(7), // Default 7 days terms
                'subtotal' => $subtotal,
                'tax_total' => $taxTotal,
                'tax_type' => $taxType,
                'tax_rate' => 0, // No longer used at invoice level
                'total' => $total,
                'amount_paid' => 0.00,
                'status' => InvoiceStatus::DRAFT,
            ]);

            // Calculate start and end dates for the billing cycle
            $startDate = Carbon::parse($subscription->next_due_date ?? $subscription->start_date);
            $endDate = match ($service->billing_cycle) {
                \App\Enums\BillingCycle::MONTHLY => $startDate->copy()->addMonth()->subDay(),
                \App\Enums\BillingCycle::QUARTERLY => $startDate->copy()->addMonths(3)->subDay(),
                \App\Enums\BillingCycle::SEMI_ANNUALLY => $startDate->copy()->addMonths(6)->subDay(),
                \App\Enums\BillingCycle::ANNUALLY => $startDate->copy()->addYear()->subDay(),
                \App\Enums\BillingCycle::ONE_TIME => null,
            };

            $cycleLabel = ucfirst(str_replace('_', '-', $service->billing_cycle->value));
            $description = 'Renewal for: ' . $service->name . ' (' . $cycleLabel . ')';
            
            if ($endDate) {
                $description .= "\n[" . $startDate->format('d M Y') . ' to ' . $endDate->format('d M Y') . ']';
            }

            // Attach Line Item
            $invoice->items()->create([
                'description' => $description,
                'hsn_code' => $service->hsn_code,
                'quantity' => 1,
                'unit_price' => $subtotal,
                'tax_rate' => $taxRate,
                'total' => $subtotal,
            ]);

            // Notify client if they have an email
            if ($subscription->client && $subscription->client->email) {
                $subscription->client->notify(new \App\Notifications\InvoiceGeneratedNotification($invoice));
            }

            return $invoice;
        });
    }
}
