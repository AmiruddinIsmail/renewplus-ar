<?php

namespace App\Models\Enums;

enum InvoiceStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case OVERDUE = 'overdue';
    case PAID = 'paid';
    case PARTIAL_PAID = 'partial';
}
