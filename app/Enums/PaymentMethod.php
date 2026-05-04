<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentMethod: string
{
    case CASH = 'cash';
    case BANK_TRANSFER = 'bank_transfer';
    case STRIPE = 'stripe';
    case RAZORPAY = 'razorpay';
    case PAYPAL = 'paypal';
}
