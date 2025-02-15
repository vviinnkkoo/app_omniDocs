<?php
namespace App\Services;

use App\Models\Receipt;
use App\Models\OrderItemList;
use App\Models\WorkYears;

class GlobalService
{
    // Receipt :: global functions
    public static function getLatestReceiptNumber($year)
    {
        return (Receipt::where('year', $year)->orderBy('number', 'desc')->value('number')) + 1;
    }

    public static function calculateReceiptTotal($order_id)
    {
        $receipt = Receipt::with(['order.deliveryService'])->where('order_id', $order_id)->firstOrFail();
        $order = $receipt->order;
        $deliveryService = $order->deliveryService;

        $subtotal = self::calculateReceiptSubtotal($receipt->order_id);
        $deliveryCost = str_replace(',', '.', $deliveryService->default_cost);
        $total = number_format(($subtotal + $deliveryCost), 2, ',', '.');

        return $total;
    }

    public static function calculateReceiptSubtotal($order_id)
    {
        return OrderItemList::where('order_id', $order_id)
            ->selectRaw('SUM(amount * price * ( ( 100 - discount ) / 100 )) as subtotal')
            ->pluck('subtotal')
            ->first();
    }

    public static function calculateTotalForAllReceipts($year)
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
            $order = $receipt->order;
            $deliveryService = $order->deliveryService;

            $subtotal = self::calculateReceiptSubtotal($receipt->order_id);
            $deliveryCost = str_replace(',', '.', $deliveryService->default_cost);

            $totalSum += ($subtotal + $deliveryCost);
        }

        return number_format(($totalSum), 2, ',', '.');
    }

    public static function countReceipts($year)
    {
        return Receipt::where('is_cancelled', 0)->where('year', $year)->count();
    }

    // OrderItemList :: global functions
    public static function sumWholeOrder($id)
    {
        return OrderItemList::where('order_id', $id)
            ->selectRaw('SUM(amount * price * ( ( 100 - discount ) / 100 )) as total')
            ->pluck('total')
            ->first();
    }

    public static function sumSingleOrderItem($id)
    {
        $total = OrderItemList::where('id', $id)
            ->selectRaw('SUM(amount * price * ( ( 100 - discount ) / 100 )) as total')
            ->pluck('total')
            ->first();

        return number_format($total, 2, ',', '.');
    }
}