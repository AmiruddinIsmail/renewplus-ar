<?php

namespace App\Features\Automation\Utils;

use Carbon\Carbon;
use Exception;

class AutomationUtil
{
    public static function invoiceCreatableAt(Carbon $runningAt): array
    {
        try {

            $days = [$runningAt->day];

            if ($runningAt->isEndOfMonth()) {

                if ($runningAt->day === 28) {
                    $days = array_merge($days, [29, 30, 31]);
                }

                if ($runningAt->day === 29) {
                    $days = array_merge($days, [30, 31]);
                }

                if ($runningAt->day === 30) {
                    $days = array_merge($days, [31]);
                }
            }

            return $days;

        } catch (Exception $e) {

            return [$runningAt->day];

        }
    }
}
