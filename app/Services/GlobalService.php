<?php
namespace App\Services;

use App\Models\Receipt;
use App\Models\OrderItemList;
use App\Models\WorkYears;
use App\Models\Kpr;
use App\Models\KprItemList;
use App\Models\Order;

class GlobalService
{
    // Define the query strings as a constant
    const ORDER_ITEM_SUM_QUERY = 'SUM(amount * price * ( ( 100 - discount ) / 100 )) as ';
    const ORDER_ITEM_SUM_ALIAS = 'calculation';

    // Receipt :: Global functions
    public static function getLatestReceiptNumber($year)
    {
        return (Receipt::where('year', $year)->orderBy('number', 'desc')->value('number')) + 1;
    }


    public static function calculateReceiptTotal($id)
    {
        $order = Order::with(['deliveryService'])->findOrFail($id);
        $deliveryCost = $order->deliveryService->default_cost;
        $subtotal = self::sumWholeOrder($id);

        return $subtotal + $deliveryCost;
    }

    
    public static function calculateTotalForAllReceiptsInYear($year)
    {
        $query = Receipt::with(['order.deliveryService'])->where('is_cancelled', 0);
        $workingYears = WorkYears::pluck('year')->toArray();

        if (in_array($year, $workingYears)) {
            $query->where('year', $year);
        } elseif ($year === 3) {
            $query->whereNotNull('paid_date');
        }

        $receipts = $query->get();
        $totalSum = 0;

        foreach ($receipts as $receipt) {
            $totalSum += self::calculateReceiptTotal($receipt->order_id);
        }

        return $totalSum;
    }


    public static function countReceipts($year)
    {
        return Receipt::where('is_cancelled', 0)->where('year', $year)->count();
    }


    private static function getOrderItemSumQuery($alias)
    {
        return self::ORDER_ITEM_SUM_QUERY . $alias;
    }


    // OrderItemList :: Global functions
    public static function sumWholeOrder($id)
    {
        return OrderItemList::where('order_id', $id)
            ->selectRaw(self::getOrderItemSumQuery(self::ORDER_ITEM_SUM_ALIAS))
            ->pluck(self::ORDER_ITEM_SUM_ALIAS)
            ->first();
    }


    public static function sumSingleOrderItem($id)
    {        
        return OrderItemList::where('id', $id)
            ->selectRaw(self::getOrderItemSumQuery(self::ORDER_ITEM_SUM_ALIAS))
            ->pluck(self::ORDER_ITEM_SUM_ALIAS)
            ->first();
    }

    // KPR :: Global functions
    public static function sumAllPaymentsInYear($year)
    {
        return Kpr::whereYear('date', $year)->sum('amount');
    }


    public static function countAllPaymentsInYear($year)
    {
        return Kpr::whereYear('date', $year)->count();
    }

    
    public static function sumAllReciepesFromKpr($id)
    {
        $kprItemList = KprItemList::where('kpr_id', $id)->get();
        $totalSum = 0;

        foreach ($kprItemList as $item) {
            $totalSum += self::calculateReceiptTotal($item->receipt->order_id);            
        }

        return $totalSum;
    }
}