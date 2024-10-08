<?php

namespace App\Utils;

use Carbon\Carbon;

class Helper
{
    public static function formatMoney(int $money): string
    {
        return number_format($money / 100, 2, '.', ',');
    }

    public static function monthlyAniversaryDays(Carbon $today): array
    {
        try {
            $days = [$today->day];
            if ($today->isEndOfMonth()) {
                if ($today->day == 28) {
                    $days = array_merge($days, [29, 30, 31]);
                }
                if ($today->day == 29) {
                    $days = array_merge($days, [30, 31]);
                }
                if ($today->day == 30) {
                    $days = array_merge($days, [31]);
                }
            }

            return $days;
        } catch (\Exception $e) {
            return [$today->day];
        }
    }

    public static function referenceNoConvention(string $prefix, int $runningNo, Carbon $today): string
    {
        return $prefix.'-'.$today->format('Ymd').'-'.str_pad($runningNo, 6, '0', STR_PAD_LEFT);
    }
}
