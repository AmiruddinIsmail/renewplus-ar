<?php

namespace App\Features\Automation\Facades;

use App\Features\Automation\Services\PaymentAutomation;
use Illuminate\Support\Facades\Facade;

class PaymentProcessor extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return PaymentAutomation::class;
    }
}
