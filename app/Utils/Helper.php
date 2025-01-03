<?php

namespace App\Utils;

use Carbon\Carbon;

class Helper
{
    /**
     * Summary of formatMoney
     */
    public static function formatMoney(int $money): string
    {
        return number_format($money / 100, 2, '.', ',');
    }

    /**
     * Summary of referenceNoConvention
     */
    public static function referenceNoConvention(string $prefix, int $runningNo, Carbon $dateAt): string
    {
        return $prefix . '-' . $dateAt->format('ymd') . '-' . str_pad($runningNo, 4, '0', STR_PAD_LEFT);
    }

    public static function convertPayloadToQueryParamsWithoutEncode(array $params): string
    {
        return implode('&', array_map(
            fn ($key, $value): string => "{$key}={$value}",
            array_keys($params),
            $params
        ));
    }
}
