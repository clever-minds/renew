<?php

declare(strict_types=1);

namespace App\Http\Requests\Tenant;

use App\Enums\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'invoice_id' => [
                'required', 
                'integer',
                // Ensure the invoice actually belongs to the active tenant
                Rule::exists('invoices', 'id')->where('tenant_id', session('tenant_id'))
            ],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', new Enum(PaymentMethod::class)],
            'transaction_reference' => ['nullable', 'string', 'max:255'],
        ];
    }
}
