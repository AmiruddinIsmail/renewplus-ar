<?php

namespace App\Features\Automation\Facades;

use App\Features\Automation\Services\LateChargeAutomation;
use Illuminate\Support\Facades\Facade;

class LateChargeProcessor extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return LateChargeAutomation::class;
    }
}
