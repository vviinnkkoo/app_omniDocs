<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receipt;
use App\Models\Order;
use App\Models\DeliveryService;
use App\Models\OrderItemList;
use App\Models\WorkYears;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Services\GlobalService;

class ReceiptController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show($year)
    {
        $receipts = Receipt::where('year', $year)->orderBy('number')->paginate(25);
        $orders = Order::whereNull('date_cancelled')->orderBy('id')->get();
        $latest = GlobalService::getLatestReceiptNumber($year);

        foreach ($receipts as $receipt) {
            $receipt->totalAmount = $this->getReceiptTotal($receipt->order_id);
        }

        return view('receipts', [
            'receipts' => $receipts,
            'orders' => $orders,
            'latest' => $latest
        ]);
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'year' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        Receipt::create($request->only('number', 'order_id', 'year'));

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $record = Receipt::findOrFail($id);
        $record->update($request->only('field', 'newValue'));

        return response()->json(['message' => 'Payment type updated successfully']);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $record = Receipt::findOrFail($id);

        if ($record->delete()) {
            return response()->json(['message' => 'Record deleted successfully']);
        }

        return response()->json(['message' => 'Error deleting the record'], 500);
    }

    public function updateIsCancelledStatus(Request $request, $id)
    {
        $receipt = Receipt::findOrFail($id);
        $receipt->update(['is_cancelled' => !$receipt->is_cancelled]);
    }

    public static function getReceiptTotal($order_id)
    {
        $receipt = Receipt::where('order_id', $order_id)->firstOrFail();
        $order = $receipt->order;
        $deliveryService = $order->deliveryService;

        $subtotal = self::calculateSubtotal($receipt->order_id);
        $deliveryCost = str_replace(',', '.', $deliveryService->default_cost);
        $total = number_format(($subtotal + $deliveryCost), 2, ',', '.');

        return $total;
    }

    public static function getTotalForAllReceipts($mode)
    {
        $query = Receipt::where('is_cancelled', 0);
        $workingYears = WorkYears::pluck('year')->toArray();

        if (in_array($mode, $workingYears)) {
            $query->where('year', $mode);
        } elseif ($mode === 3) {
            $query->whereNotNull('paid_date');
        }

        $receipts = $query->get();
        $totalSum = 0;

        foreach ($receipts as $receipt) {
            $order = $receipt->order;
            $deliveryService = $order->deliveryService;

            $subtotal = self::calculateSubtotal($receipt->order_id);
            $deliveryCost = str_replace(',', '.', $deliveryService->default_cost);

            $totalSum += ($subtotal + $deliveryCost);
        }

        return number_format(($totalSum), 2, ',', '.');
    }

    public static function countReceipts($year)
    {
        return Receipt::where('is_cancelled', 0)->where('year', $year)->count();
    }

    public static function calculateSubtotal($order_id)
    {
        return OrderItemList::where('order_id', $order_id)
            ->selectRaw('SUM(amount * price * ( ( 100 - discount ) / 100 )) as subtotal')
            ->pluck('subtotal')
            ->first();
    }
}