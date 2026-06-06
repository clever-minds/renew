@php
    $currencySymbols = [
        'INR' => '₹',
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£',
        'AED' => 'د.إ',
        'CAD' => 'C$',
        'AUD' => 'A$',
        'SGD' => 'S$',
        'JPY' => '¥',
    ];
    $symbol = $currencySymbols[$company['currency'] ?? 'INR'] ?? ($company['currency'] ?? '₹');
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        @page {
            margin: 0px;
        }
        body { 
            font-family: 'Inter', 'DejaVu Sans', 'Helvetica Neue', 'Helvetica', Arial, sans-serif; 
            color: #111827; 
            margin: 0;
            padding: 0;
            font-size: 13px;
            line-height: 1.5;
        }
        .container {
            padding: 40px 50px;
        }
        .top-bar {
            height: 10px;
            background-color: #4f46e5;
            width: 100%;
        }
        .header {
            width: 100%;
            margin-bottom: 40px;
            margin-top: 10px;
        }
        .header td {
            vertical-align: top;
        }
        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #111827;
            margin: 0;
            padding: 0;
            line-height: 1;
        }
        .company-tagline {
            font-size: 10px;
            color: #4f46e5;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 4px;
        }
        .invoice-title {
            font-size: 42px;
            font-weight: bold;
            color: #111827;
            text-align: right;
            margin: 0;
            line-height: 1;
        }
        .invoice-number {
            font-size: 16px;
            color: #9ca3af;
            font-weight: bold;
            text-align: right;
            margin-top: 5px;
        }
        
        .addresses {
            width: 100%;
            margin-bottom: 30px;
        }
        .addresses td {
            vertical-align: top;
            width: 50%;
        }
        .section-title {
            font-size: 10px;
            font-weight: bold;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            border-bottom: 1px solid #f3f4f6;
            padding-bottom: 5px;
            width: 80%;
        }
        .address-text {
            color: #4b5563;
            font-size: 13px;
        }
        .address-name {
            font-weight: bold;
            color: #111827;
            font-size: 15px;
            margin-bottom: 2px;
        }

        .meta-box {
            background-color: #f9fafb;
            border: 1px solid #f3f4f6;
            border-radius: 8px;
            width: 100%;
            margin-bottom: 40px;
        }
        .meta-box td {
            padding: 15px 20px;
            width: 33.33%;
            vertical-align: top;
        }
        .meta-label {
            font-size: 10px;
            font-weight: bold;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .meta-value {
            font-size: 13px;
            font-weight: bold;
            color: #111827;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .status-paid { background-color: #d1fae5; color: #047857; }
        .status-unpaid { background-color: #fef3c7; color: #b45309; }
        .status-draft { background-color: #e5e7eb; color: #374151; }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        .items-table th {
            padding: 12px 10px;
            border-bottom: 2px solid #111827;
            font-size: 10px;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-align: left;
        }
        .items-table td {
            padding: 15px 10px;
            border-bottom: 1px solid #f3f4f6;
        }
        .item-desc {
            font-weight: bold;
            color: #111827;
            font-size: 13px;
        }
        .item-qty, .item-price {
            color: #4b5563;
        }
        .item-total {
            font-weight: bold;
            color: #111827;
        }

        .totals-wrapper {
            width: 100%;
        }
        .totals-wrapper td {
            vertical-align: top;
        }
        .totals-box {
            background-color: #f9fafb;
            border: 1px solid #f3f4f6;
            border-radius: 8px;
            padding: 20px;
            width: 250px;
            float: right;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 5px 0;
            font-size: 13px;
        }
        .totals-label {
            color: #6b7280;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .totals-value {
            text-align: right;
            font-weight: bold;
            color: #111827;
        }
        .total-final td {
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            margin-top: 10px;
        }
        .total-final .totals-label {
            color: #111827;
            font-size: 11px;
        }
        .total-final .totals-value {
            font-size: 24px;
            color: #4f46e5;
            font-weight: bold;
        }

        .footer {
            clear: both;
            margin-top: 50px;
            padding-top: 30px;
            border-top: 2px solid #f3f4f6;
        }
        .footer-table {
            width: 100%;
        }
        .footer-table td {
            vertical-align: top;
            width: 50%;
            padding-right: 20px;
        }
        .footer-text {
            color: #4b5563;
            font-size: 11px;
            line-height: 1.6;
        }
        .footer-label {
            color: #9ca3af;
            display: inline-block;
            width: 95px;
        }
    </style>
</head>
<body>
    <div class="top-bar"></div>
    <div class="container">
        
        <table class="header">
            <tr>
                <td style="width: 50%;">
                    <!-- Simulated Icon and Logo -->
                    @if(!empty($company['logo_url']))
                        @php
                            $logoPath = storage_path('app/public/' . $company['logo_url']);
                            $logoData = '';
                            if(file_exists($logoPath)){
                                $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                                $data = file_get_contents($logoPath);
                                $logoData = 'data:image/' . $type . ';base64,' . base64_encode($data);
                            }
                        @endphp
                        @if($logoData)
                            <img src="{{ $logoData }}" style="max-height: 50px; max-width: 250px; object-fit: contain; margin-bottom: 5px;">
                        @else
                            <div class="company-name">{{ $company['company_name'] ?? $tenant->name ?? 'RenewPilot' }}</div>
                            @if(!empty($company['company_tagline']))
                                <div class="company-tagline">{{ $company['company_tagline'] }}</div>
                            @endif
                        @endif
                    @else
                        <div class="company-name">{{ $company['company_name'] ?? $tenant->name ?? 'RenewPilot' }}</div>
                        @if(!empty($company['company_tagline']))
                            <div class="company-tagline">{{ $company['company_tagline'] }}</div>
                        @endif
                    @endif
                </td>
                <td style="width: 50%; text-align: right;">
                    <div class="invoice-title">INVOICE</div>
                    <div class="invoice-number">#{{ $invoice->invoice_number }}</div>
                </td>
            </tr>
        </table>

        <table class="addresses">
            <tr>
                <td>
                    <div class="section-title">From</div>
                    <div class="address-name">{{ $company['company_name'] ?? $tenant->name ?? 'RenewPilot Inc.' }}</div>
                    <div class="address-text">
                        {{ $company['address_line1'] ?? '123 Business Avenue, Suite 100' }}<br>
                        {{ $company['address_city'] ?? 'New York' }}{{ isset($company['address_state']) ? ', ' . $company['address_state'] : ', NY 10001' }}<br>
                        @if(!empty($company['support_phone']))
                            {{ $company['support_phone'] }}<br>
                        @endif
                        @if(!empty($company['tax_number']))
                            <span style="font-weight: bold;">GSTIN / VAT:</span> {{ $company['tax_number'] }}<br>
                        @endif
                        <span style="margin-top: 5px; display: block;">{{ $company['support_email'] ?? $tenant->email ?? 'support@renewpilot.com' }}</span>
                    </div>
                </td>
                <td>
                    <div class="section-title">Bill To</div>
                    <div class="address-name">{{ $client->name }}</div>
                    <div class="address-text">
                        @if($client->company)
                            {{ $client->company }}<br>
                        @endif
                        {{ $client->email }}<br>
                        @if($client->phone)
                            {{ $client->phone }}<br>
                        @endif
                        @if($client->gst_number)
                            <span style="font-weight: bold;">GSTIN:</span> {{ $client->gst_number }}
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <table class="meta-box" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <div class="meta-label">Issue Date</div>
                    <div class="meta-value">{{ \Carbon\Carbon::parse($invoice->issue_date)->format('M d, Y') }}</div>
                </td>
                <td>
                    <div class="meta-label">Due Date</div>
                    <div class="meta-value">{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</div>
                </td>
                <td>
                    <div class="meta-label">Payment Status</div>
                    <div class="status-badge status-{{ strtolower(is_string($invoice->status) ? $invoice->status : $invoice->status->value) }}">
                        {{ ucwords(str_replace('_', ' ', strtolower(is_string($invoice->status) ? $invoice->status : $invoice->status->value))) }}
                    </div>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 35%;">Description</th>
                    <th style="width: 15%; text-align: center;">HSN/SAC</th>
                    <th style="width: 10%; text-align: center;">Qty</th>
                    <th style="width: 15%; text-align: right;">Price</th>
                    <th style="width: 10%; text-align: right;">GST %</th>
                    <th style="width: 15%; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td class="item-desc">{{ $item->description }}</td>
                    <td class="item-qty" style="text-align: center;">{{ $item->hsn_code ?? '-' }}</td>
                    <td class="item-qty" style="text-align: center;">{{ (int)$item->quantity }}</td>
                    <td class="item-price" style="text-align: right;">{{ $symbol }}{{ number_format($item->unit_price, 2) }}</td>
                    <td class="item-qty" style="text-align: right;">{{ number_format($item->tax_rate, 2) }}%</td>
                    <td class="item-total" style="text-align: right;">{{ $symbol }}{{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals-wrapper">
            <div class="totals-box">
                <table class="totals-table">
                    <tr>
                        <td class="totals-label">Subtotal</td>
                        <td class="totals-value">{{ $symbol }}{{ number_format($invoice->subtotal, 2) }}</td>
                    </tr>
                    @if($invoice->tax_total > 0)
                        @if($invoice->tax_type === 'cgst_sgst')
                        <tr>
                            <td class="totals-label">Total CGST</td>
                            <td class="totals-value">{{ $symbol }}{{ number_format($invoice->tax_total / 2, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="totals-label">Total SGST</td>
                            <td class="totals-value">{{ $symbol }}{{ number_format($invoice->tax_total / 2, 2) }}</td>
                        </tr>
                        @elseif($invoice->tax_type === 'igst')
                        <tr>
                            <td class="totals-label">Total IGST</td>
                            <td class="totals-value">{{ $symbol }}{{ number_format($invoice->tax_total, 2) }}</td>
                        </tr>
                        @else
                        <tr>
                            <td class="totals-label">Tax</td>
                            <td class="totals-value">{{ $symbol }}{{ number_format($invoice->tax_total, 2) }}</td>
                        </tr>
                        @endif
                    @endif
                    @if($invoice->amount_paid > 0)
                    <tr>
                        <td class="totals-label">Amount Paid</td>
                        <td class="totals-value" style="color: #047857;">-{{ $symbol }}{{ number_format($invoice->amount_paid, 2) }}</td>
                    </tr>
                    <tr class="total-final">
                        <td class="totals-label">Balance Due</td>
                        <td class="totals-value">{{ $symbol }}{{ number_format($invoice->total - $invoice->amount_paid, 2) }}</td>
                    </tr>
                    @else
                    <tr class="total-final">
                        <td class="totals-label">Total Due</td>
                        <td class="totals-value">{{ $symbol }}{{ number_format($invoice->total, 2) }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <div class="footer">
            @php
                $qrData = '';
                $hasBankDetails = !empty($company['bank_name']) || !empty($company['bank_account']);
                $hasQrCode = !empty($company['qr_code_url']);

                if($hasQrCode) {
                    $qrPath = storage_path('app/public/' . $company['qr_code_url']);
                    if(file_exists($qrPath)){
                        $type = pathinfo($qrPath, PATHINFO_EXTENSION);
                        $data = file_get_contents($qrPath);
                        $qrData = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    }
                } elseif (!$hasBankDetails) {
                    $defaultQrPath = public_path('default_qr.png');
                    if(file_exists($defaultQrPath)){
                        $type = pathinfo($defaultQrPath, PATHINFO_EXTENSION);
                        $data = file_get_contents($defaultQrPath);
                        $qrData = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    }
                }

                $bankName = $company['bank_name'] ?? '';
                $bankAccount = $company['bank_account'] ?? '';
                $bankIfsc = $company['bank_ifsc'] ?? $company['bank_routing'] ?? '';
                $bankAddress = $company['bank_address'] ?? '';

                if (!$hasBankDetails && !$hasQrCode) {
                    $bankName = 'Indusind Bank';
                    $bankAccount = '249998144401';
                    $bankIfsc = 'INDB000012';
                    $bankAddress = 'Vasna Road';
                }
            @endphp
            <table class="footer-table">
                <tr>
                    <td style="width: {{ $qrData ? '40%' : '50%' }};">
                        <div class="section-title">Payment Details</div>
                        <div class="footer-text">
                            @if(!empty($bankName)) <span class="footer-label">Bank Name:</span> <strong style="color: #111827;">{{ $bankName }}</strong><br> @endif
                            @if(!empty($bankAccount)) <span class="footer-label">Account No:</span> <strong style="color: #111827;">{{ $bankAccount }}</strong><br> @endif
                            @if(!empty($bankIfsc)) <span class="footer-label">IFSC Code:</span> <strong style="color: #111827;">{{ $bankIfsc }}</strong><br> @endif
                            @if(!empty($bankAddress)) <span class="footer-label">Bank Address:</span> <strong style="color: #111827;">{{ $bankAddress }}</strong> @endif
                        </div>
                    </td>
                    @if($qrData)
                    <td style="width: 20%; text-align: center; vertical-align: middle; padding-right: 20px;">
                        <div style="padding: 5px; border: 2px solid #4b5563; border-radius: 8px; display: inline-block; background-color: #fff;">
                            <img src="{{ $qrData }}" style="width: 80px; height: 80px; object-fit: contain;">
                        </div>
                    </td>
                    @endif
                    <td style="width: {{ $qrData ? '40%' : '50%' }};">
                        <div class="section-title">Terms & Conditions</div>
                        <div class="footer-text font-medium">
                            {{ $company['terms_conditions'] ?? 'Please remit payment within 14 days of receiving this invoice. There will be a 1.5% interest charge per month on late invoices. Thank you for your business!' }}
                        </div>
                    </td>
                </tr>
            </table>
        </div>

    </div>
</body>
</html>
