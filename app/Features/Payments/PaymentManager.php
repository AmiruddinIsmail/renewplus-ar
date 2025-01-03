<?php

namespace App\Features\Payments;

use App\Features\Payments\Contracts\PaymentDriver;
use App\Features\Payments\Drivers\CurlecDriver;
use Illuminate\Support\Manager;

class PaymentManager extends Manager
{
    public function createCurlecDriver(): PaymentDriver
    {
        return resolve(CurlecDriver::class);
    }

    public function getDefaultDriver(): string
    {
        return config('services.payments.default');
    }
}
