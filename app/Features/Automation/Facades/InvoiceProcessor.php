<?php

namespace App\Features\Automation\Facades;

use App\Features\Automation\Services\InvoiceAutomation;
use Illuminate\Support\Facades\Facade;

class InvoiceProcessor extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return InvoiceAutomation::class;
    }
}
