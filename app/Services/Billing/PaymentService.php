<?php

declare(strict_types=1);

namespace App\Services\Billing;

use App\Models\Invoice; // Note: Model assumed to be created later
use App\Enums\InvoiceStatus;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentService
{
    public function process(int $invoiceId, float $amount, string $paymentMethod, ?string $transactionRef = null): void
    {
        DB::transaction(function () use ($invoiceId, $amount, $paymentMethod, $transactionRef) {
            $invoice = DB::table('invoices')->where('id', $invoiceId)->lockForUpdate()->first();
            
            if (!$invoice) {
                throw new \Exception("Invoice not found.");
            }

            $newAmountPaid = (float) $invoice->amount_paid + $amount;
            $status = InvoiceStatus::PARTIAL->value;

            if ($newAmountPaid >= (float) $invoice->total) {
                $status = InvoiceStatus::PAID->value;
            }

            // Log Payment
            DB::table('payments')->insert([
                'tenant_id' => $invoice->tenant_id,
                'invoice_id' => $invoice->id,
                'amount' => $amount,
                'payment_method' => $paymentMethod,
                'transaction_reference' => $transactionRef,
                'payment_date' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Update Invoice
            DB::table('invoices')->where('id', $invoice->id)->update([
                'amount_paid' => $newAmountPaid,
                'status' => $status,
                'updated_at' => Carbon::now(),
            ]);

            // Here we would typically dispatch an event like PaymentReceived
            // Event::dispatch(new PaymentReceived($invoice->id));
        });
    }
}
