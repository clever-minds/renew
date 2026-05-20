<?php
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
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice <?php echo e($invoice->invoice_number); ?></title>
    <style>
        @page {
            margin: 0px;
        }
        body { 
            font-family: 'DejaVu Sans', 'Helvetica Neue', 'Helvetica', Arial, sans-serif; 
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
                    <div class="company-name"><?php echo e($company['company_name'] ?? $tenant->name ?? 'RenewPilot'); ?></div>
                    <div class="company-tagline">SaaS Solutions</div>
                </td>
                <td style="width: 50%; text-align: right;">
                    <div class="invoice-title">INVOICE</div>
                    <div class="invoice-number">#<?php echo e($invoice->invoice_number); ?></div>
                </td>
            </tr>
        </table>

        <table class="addresses">
            <tr>
                <td>
                    <div class="section-title">From</div>
                    <div class="address-name"><?php echo e($company['company_name'] ?? $tenant->name ?? 'RenewPilot Inc.'); ?></div>
                    <div class="address-text">
                        <?php echo e($company['address_line1'] ?? '123 Business Avenue, Suite 100'); ?><br>
                        <?php echo e($company['address_city'] ?? 'New York'); ?><?php echo e(isset($company['address_state']) ? ', ' . $company['address_state'] : ', NY 10001'); ?><br>
                        <?php if(!empty($company['support_phone'])): ?>
                            <?php echo e($company['support_phone']); ?><br>
                        <?php endif; ?>
                        <span style="margin-top: 5px; display: block;"><?php echo e($company['support_email'] ?? $tenant->email ?? 'support@renewpilot.com'); ?></span>
                    </div>
                </td>
                <td>
                    <div class="section-title">Bill To</div>
                    <div class="address-name"><?php echo e($client->name); ?></div>
                    <div class="address-text">
                        <?php if($client->company): ?>
                            <?php echo e($client->company); ?><br>
                        <?php endif; ?>
                        <?php echo e($client->email); ?><br>
                        <?php if($client->phone): ?>
                            <?php echo e($client->phone); ?>

                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        </table>

        <table class="meta-box" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <div class="meta-label">Issue Date</div>
                    <div class="meta-value"><?php echo e(\Carbon\Carbon::parse($invoice->issue_date)->format('M d, Y')); ?></div>
                </td>
                <td>
                    <div class="meta-label">Due Date</div>
                    <div class="meta-value"><?php echo e(\Carbon\Carbon::parse($invoice->due_date)->format('M d, Y')); ?></div>
                </td>
                <td>
                    <div class="meta-label">Payment Status</div>
                    <div class="status-badge status-<?php echo e(strtolower(is_string($invoice->status) ? $invoice->status : $invoice->status->value)); ?>">
                        <?php echo e(ucfirst(is_string($invoice->status) ? $invoice->status : $invoice->status->value)); ?>

                    </div>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50%;">Description</th>
                    <th style="width: 15%; text-align: center;">Qty</th>
                    <th style="width: 17.5%; text-align: right;">Price</th>
                    <th style="width: 17.5%; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="item-desc"><?php echo e($item->description); ?></td>
                    <td class="item-qty" style="text-align: center;"><?php echo e((int)$item->quantity); ?></td>
                    <td class="item-price" style="text-align: right;"><?php echo e($symbol); ?><?php echo e(number_format($item->unit_price, 2)); ?></td>
                    <td class="item-total" style="text-align: right;"><?php echo e($symbol); ?><?php echo e(number_format($item->total, 2)); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <div class="totals-wrapper">
            <div class="totals-box">
                <table class="totals-table">
                    <tr>
                        <td class="totals-label">Subtotal</td>
                        <td class="totals-value"><?php echo e($symbol); ?><?php echo e(number_format($invoice->subtotal, 2)); ?></td>
                    </tr>
                    <?php if($invoice->tax_total > 0): ?>
                    <tr>
                        <td class="totals-label">Tax</td>
                        <td class="totals-value"><?php echo e($symbol); ?><?php echo e(number_format($invoice->tax_total, 2)); ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr class="total-final">
                        <td class="totals-label">Total Due</td>
                        <td class="totals-value"><?php echo e($symbol); ?><?php echo e(number_format($invoice->total, 2)); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="footer">
            <table class="footer-table">
                <tr>
                    <td>
                        <div class="section-title">Payment Details</div>
                        <div class="footer-text">
                            <span class="footer-label">Bank Name:</span> <strong style="color: #111827;"><?php echo e($company['bank_name'] ?? 'Chase Bank'); ?></strong><br>
                            <span class="footer-label">Account No:</span> <strong style="color: #111827;"><?php echo e($company['bank_account'] ?? '1234 5678 9000'); ?></strong><br>
                            <span class="footer-label">IFSC Code:</span> <strong style="color: #111827;"><?php echo e($company['bank_ifsc'] ?? $company['bank_routing'] ?? '123456789'); ?></strong><br>
                            <?php if(!empty($company['bank_address'])): ?>
                                <span class="footer-label">Bank Address:</span> <strong style="color: #111827;"><?php echo e($company['bank_address']); ?></strong>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div class="section-title">Terms & Conditions</div>
                        <div class="footer-text font-medium">
                            <?php echo e($company['terms_conditions'] ?? 'Please remit payment within 14 days of receiving this invoice. There will be a 1.5% interest charge per month on late invoices. Thank you for your business!'); ?>

                        </div>
                    </td>
                </tr>
            </table>
        </div>

    </div>
</body>
</html>
<?php /**PATH /var/www/html/ReNewPilot_new/resources/views/app/invoices/pdf.blade.php ENDPATH**/ ?>