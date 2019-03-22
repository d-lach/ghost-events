<?php

namespace App\Libs\Utilities;


class Random
{
    static function int(int $min = 0, int $max = 100)
    {
        return rand($min, $max- 1);
    }

    static function float(int $min = 0, int $max = 100)
    {
        return rand($min, $max - 1) + (rand(0, 1000) / 1000);
    }

    // dates format Y-m-d H:i vel YYYY-MM-DD HH:mm
    static function date(string $startDate, string $endDate)
    {
        // Convert to timetamps
        $min = strtotime($startDate);
        $max = strtotime($endDate);

        // Generate random number using above bounds
        $val = rand($min, $max);

        // Convert back to desired date format
        return date('Y-m-d H:i', $val);
    }
}