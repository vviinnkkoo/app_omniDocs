<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

use App\Models\Invoice;
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
    | Get the latest Invoice number for the given year
    |--------------------------------------------------------------------------------------------
    */
    public static function getLatestInvoiceNumber($year)
    {
        return (Invoice::where('year', $year)->orderBy('number', 'desc')->value('number')) + 1;
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Calculate the total amount for a given Invoice by its order ID
    |--------------------------------------------------------------------------------------------
    */
    public static function calculateInvoiceTotal($id)
    {
        $order = Order::with(['deliveryService'])->findOrFail($id);
        $deliveryCost = $order->deliveryService->default_cost;
        $subtotal = self::sumOrderItems(orderId: $id);
        return $subtotal + $deliveryCost;
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Calculate the total amount for all Invoices in a given year
    |--------------------------------------------------------------------------------------------
    */
    public static function calculateTotalForAllInvoicesInYear(int $year): float
    {
        return DB::table('invoices')
            ->join('orders', 'invoices.order_id', '=', 'orders.id')
            ->join('order_item_lists', 'orders.id', '=', 'order_item_lists.order_id')
            ->where('invoices.is_cancelled', 0)
            ->where('invoices.year', $year)
            ->selectRaw('SUM(' . self::itemSumFormula() . ') as items_total')
            ->value('items_total') ?? 0;
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Count all non-cancelled invoices in a given year
    |--------------------------------------------------------------------------------------------
    */
    public static function countInvoices($year)
    {
        return Invoice::where('is_cancelled', 0)->where('year', $year)->count();
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
    | Sum all Invoices associated with a specific KPR entry by its ID
    |--------------------------------------------------------------------------------------------
    */
    public static function sumAllInvoicesFromKpr(int $kprId): float
    {
        return KprItemList::where('kpr_id', $kprId)
            ->get()
            ->sum(fn($item) => self::calculateInvoiceTotal($item->invoice->order_id));
    }
}