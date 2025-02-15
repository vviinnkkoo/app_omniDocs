<?php

namespace App\Services;

use App\Models\Receipt;
use App\Models\OrderItemList;

class GlobalService
{
    // Receipt :: global functions
    public static function getLatestReceiptNumber($year)
    {
        return (Receipt::where('year', $year)->orderBy('number', 'desc')->value('number')) + 1;
    }
    
    // OrderItemList :: global functions
    public static function sumWholeOrderItemList($id) {
        return OrderItemList::where('order_id', $id)
            ->selectRaw('SUM(amount * price * ( ( 100 - discount ) / 100 )) as total')
            ->pluck('total')
            ->first();
    }

    public static function sumSingleOrderItem($id) {
        $total = OrderItemList::where('id', $id)
            ->selectRaw('SUM(amount * price * ( ( 100 - discount ) / 100 )) as total')
            ->pluck('total')
            ->first();
    
        return number_format($total, 2, ',', '.');
    }
}
