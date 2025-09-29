<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

use App\Models\Receipt;
use App\Models\OrderItemList;
use App\Models\WorkYears;
use App\Models\Kpr;
use App\Models\KprItemList;
use App\Models\Order;

class GlobalService
{
    /*
    |--------------------------------------------------------------------------------------------
    | Formula for calculating item sum
    |--------------------------------------------------------------------------------------------
    */
    public static function itemSumFormula(): string
    {
        return '(amount * price * ((100 - discount) / 100))';
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Get the latest receipt number for the given year
    |--------------------------------------------------------------------------------------------
    */
    public static function getLatestReceiptNumber($year)
    {
        return (Receipt::where('year', $year)->orderBy('number', 'desc')->value('number')) + 1;
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Calculate the total amount for a given receipt by its order ID
    |--------------------------------------------------------------------------------------------
    */
    public static function calculateReceiptTotal($id)
    {
        $order = Order::with(['deliveryService'])->findOrFail($id);
        $deliveryCost = $order->deliveryService->default_cost;
        $subtotal = self::sumOrderItems(orderId: $id);
        return $subtotal + $deliveryCost;
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Calculate the total amount for all receipts in a given year
    |--------------------------------------------------------------------------------------------
    */
    public static function calculateTotalForAllReceiptsInYear(int $year): float
    {
        return DB::table('receipts')
            ->join('orders', 'receipts.order_id', '=', 'orders.id')
            ->join('order_item_lists', 'orders.id', '=', 'order_item_lists.order_id')
            ->where('receipts.is_cancelled', 0)
            ->where('receipts.year', $year)
            ->selectRaw('SUM(' . self::itemSumFormula() . ') as items_total')
            ->value('items_total') ?? 0;
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Count all non-cancelled receipts in a given year
    |--------------------------------------------------------------------------------------------
    */
    public static function countReceipts($year)
    {
        return Receipt::where('is_cancelled', 0)->where('year', $year)->count();
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Sum items from an order, including discount and other values
    | Single function for both cases if orderId or itemId is provided
    |--------------------------------------------------------------------------------------------
    */
    public static function sumOrderItems(?int $orderId = null, ?int $itemId = null): float
    {
        $query = OrderItemList::query();

        if ($orderId !== null) {
            $query->where('order_id', $orderId);
        }

        if ($itemId !== null) {
            $query->where('id', $itemId);
        }

        return $query
            ->selectRaw('SUM(' . self::itemSumFormula() . ') as items_total')
            ->value('items_total') ?? 0;
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Sum all payments made in a given year
    |--------------------------------------------------------------------------------------------
    */
    public static function sumAllPaymentsInYear($year)
    {
        return Kpr::whereYear('date', $year)->sum('amount');
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Count all payments made in a given year
    |--------------------------------------------------------------------------------------------
    */
    public static function countAllPaymentsInYear($year)
    {
        return Kpr::whereYear('date', $year)->count();
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Sum all receipts associated with a specific KPR entry by its ID
    |--------------------------------------------------------------------------------------------
    */
    public static function sumAllReciepesFromKpr(int $kprId): float
    {
        return DB::table('kpr_item_lists as k')
            ->join('receipts as r', 'k.receipt_id', '=', 'r.id')
            ->join('orders as o', 'r.order_id', '=', 'o.id')
            ->join('order_item_lists as oi', 'o.id', '=', 'oi.order_id')
            ->join('delivery_services as d', 'o.delivery_service_id', '=', 'd.id')
            ->where('k.kpr_id', $kprId)
            ->where('r.is_cancelled', 0)
            ->selectRaw('SUM(' . self::itemSumFormula() . ' + COALESCE(d.default_cost, 0)) as total_sum')
            ->value('total_sum') ?? 0;
    }
}