<?php

namespace App\Services;

use App\Models\Receipt;

class GlobalService
{
    public static function getLatestReceiptNumber($year)
    {
        return (Receipt::where('year', $year)->orderBy('number', 'desc')->value('number')) + 1;
    }
}
