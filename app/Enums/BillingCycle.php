<?php

declare(strict_types=1);

namespace App\Enums;

enum BillingCycle: string
{
    case ONE_TIME = 'one_time';
    case MONTHLY = 'monthly';
    case QUARTERLY = 'quarterly';
    case SEMI_ANNUALLY = 'semi_annually';
    case ANNUALLY = 'annually';
}
