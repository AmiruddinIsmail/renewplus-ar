<?php

namespace App\Utils;

class Helper
{
    public static function formatMoney(int $money): string
    {
        return number_format($money / 100, 2, '.', ',');
    }
}
