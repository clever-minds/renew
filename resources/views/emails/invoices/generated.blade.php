<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 40px;
            color: #111827;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #4f46e5;
            padding: 30px;
            text-align: center;
            color: white;
        }
        .logo-container {
            margin-bottom: 20px;
            background-color: white;
            padding: 15px;
            border-radius: 12px;
            display: inline-block;
        }
        .company-name {
            font-size: 24px;
            font-weight: 900;
            margin: 0;
        }
        .company-tagline {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 5px;
            color: #e0e7ff;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .invoice-details {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .detail-row:last-child {
            margin-bottom: 0;
        }
        .detail-label {
            color: #6b7280;
            font-weight: 500;
        }
        .detail-value {
            font-weight: bold;
        }
        .total-row {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            font-size: 18px;
            color: #4f46e5;
        }
        .message {
            line-height: 1.6;
            color: #4b5563;
            margin-bottom: 30px;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
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

    <div class="container">
        <div class="header">
            @if(!empty($company['logo_url']))
                @php
                    $logoPath = storage_path('app/public/' . $company['logo_url']);
                    $logoSrc = '';
                    if (file_exists($logoPath)) {
                        $logoSrc = isset($message) ? $message->embed($logoPath) : asset('storage/' . $company['logo_url']);
                    }
                @endphp
                @if($logoSrc)
                    <div class="logo-container">
                        <img src="{{ $logoSrc }}" alt="Logo" style="max-height: 50px; max-width: 150px; object-fit: contain;">
                    </div>
                @endif
            @endif
            <div class="company-name">{{ $company['company_name'] ?? $tenant->name ?? 'RenewPilot' }}</div>
            @if(!empty($company['company_tagline']))
                <div class="company-tagline">{{ $company['company_tagline'] }}</div>
            @endif
        </div>

        <div class="content">
            <div class="greeting">Hello {{ $client->name }},</div>
            
            <div class="message">
                A new invoice has been generated for your subscription. Please find the details below, and the full invoice is attached as a PDF document.
            </div>

            <table width="100%" cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse; background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 30px;">
                <tr>
                    <td style="padding: 20px 20px 10px 20px; color: #6b7280; font-weight: 500; font-size: 14px;">Invoice Number</td>
                    <td style="padding: 20px 20px 10px 20px; text-align: right; font-weight: bold; color: #111827; font-size: 14px;">#{{ $invoice->invoice_number }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 20px; color: #6b7280; font-weight: 500; font-size: 14px;">Description</td>
                    <td style="padding: 10px 20px; text-align: right; font-weight: bold; color: #111827; font-size: 14px;">
                        @if($invoice->items && $invoice->items->count() === 1)
                            {{ $invoice->items->first()->description }}
                        @elseif($invoice->items && $invoice->items->count() > 1)
                            {{ $invoice->items->first()->description }} (+{{ $invoice->items->count() - 1 }} more)
                        @else
                            Services rendered
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 20px; color: #6b7280; font-weight: 500; font-size: 14px;">Issue Date</td>
                    <td style="padding: 10px 20px; text-align: right; font-weight: bold; color: #111827; font-size: 14px;">{{ \Carbon\Carbon::parse($invoice->issue_date)->format('M d, Y') }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 20px 20px 20px; color: #6b7280; font-weight: 500; font-size: 14px;">Due Date</td>
                    <td style="padding: 10px 20px 20px 20px; text-align: right; font-weight: bold; color: #111827; font-size: 14px;">{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</td>
                </tr>
                @if($invoice->amount_paid > 0)
                <tr>
                    <td style="padding: 15px 20px 10px 20px; border-top: 1px solid #e5e7eb; color: #6b7280; font-weight: 500; font-size: 14px;">Amount Paid</td>
                    <td style="padding: 15px 20px 10px 20px; border-top: 1px solid #e5e7eb; text-align: right; font-weight: bold; color: #047857; font-size: 14px;">-{{ $symbol }}{{ number_format((float)$invoice->amount_paid, 2) }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 20px 20px 20px; color: #4f46e5; font-weight: bold; font-size: 18px;">Balance Due</td>
                    <td style="padding: 10px 20px 20px 20px; text-align: right; color: #4f46e5; font-weight: bold; font-size: 18px;">{{ $symbol }}{{ number_format((float)($invoice->total - $invoice->amount_paid), 2) }}</td>
                </tr>
                @else
                <tr>
                    <td style="padding: 20px; border-top: 1px solid #e5e7eb; color: #4f46e5; font-weight: bold; font-size: 18px;">Amount Due</td>
                    <td style="padding: 20px; border-top: 1px solid #e5e7eb; text-align: right; color: #4f46e5; font-weight: bold; font-size: 18px;">{{ $symbol }}{{ number_format((float)$invoice->total, 2) }}</td>
                </tr>
                @endif
            </table>

            <div class="message">
                Thank you for your business!
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} {{ $company['company_name'] ?? $tenant->name ?? 'RenewPilot' }}. All rights reserved.
        </div>
    </div>
</body>
</html>
