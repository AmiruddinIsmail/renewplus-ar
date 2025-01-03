<?php

namespace App\Models\Traits;

use Illuminate\Support\Number;

trait HasCurrency
{
    public function convertToHumanReadable(int $currency): string
    {
        return Number::currency($this->convertToDecimal($currency), 'MYR', 'ms');
    }

    public function convertToDecimal(int $currency): string
    {
        return number_format($currency / 100, 2, '.', '');

    }
}
