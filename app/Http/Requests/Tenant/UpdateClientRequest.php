<?php

declare(strict_types=1);

namespace App\Http\Requests\Tenant;

use App\Enums\ClientStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'gst_number' => ['nullable', 'string', 'max:50'],
            'status' => ['nullable', new Enum(ClientStatus::class)],
            'notes' => ['nullable', 'string'],
            'billing_address' => ['nullable', 'array'],
            'billing_address.line1' => ['nullable', 'string', 'max:255'],
            'billing_address.city' => ['nullable', 'string', 'max:255'],
            'billing_address.country' => ['nullable', 'string', 'max:255'],
        ];
    }
}
