<?php

namespace App\Features\Payments\Facades;

use App\Features\Payments\PaymentManager as PaymentProcessor;
use Illuminate\Support\Facades\Facade;

class PaymentManager extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PaymentProcessor::class;
    }
}
