<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Receipt;
use App\Models\Order;
use App\Models\DeliveryService;
use App\Models\OrderItemList;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class ReceiptController extends Controller
{
    // Protect all functions and redirect to login if necessary
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // GET function for displaying purposes
    public function show($year) {

        $receipts = Receipt::where('year', $year)
                            ->orderBy('number')
                            ->paginate(25);
        $orders = Order::whereNull('date_cancelled')
                        ->get()
                        ->sortBy('id');
        $latest = (Receipt::where('year', $year)
                            ->orderBy('number', 'desc')
                            ->limit(1)
                            ->value('number')) + 1;
        
        return view('receipts', [
            'receipts' => $receipts,
            'orders' => $orders,
            'latest' => $latest
            ]);
    }

    // POST function for saving new stuff
    public function save (Request $request) {
        $validator = Validator::make($request->all(), [
        'order_id' => 'required',
        'year' => 'required'
        ]);
            if ($validator->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validator);
            }
        $receipt = new Receipt;
        $receipt->number = $request->number;
        $receipt->order_id = $request->order_id;
        $receipt->year = $request->year;
        $receipt->save();
        
        return redirect()->back();
    }

    // UPDATE (Ajax version)
    public function update(Request $request, $id)
    {

        $record = Receipt::findOrFail($id);
        $field = $request->input('field');
        $newValue = $request->input('newValue');
        $record->$field = $newValue;
        $record->save();

        return response()->json(['message' => 'Payment type updated successfully']);
    }

    // DELETE function (Ajax version)
    public function destroy(Request $request, $id): JsonResponse
    {

        $record = Receipt::findOrFail($id);

        if (!$record) {
            return response()->json(['message' => 'Record not found'], 404);
        }
        
        if ($record->delete()) {
            return response()->json(['message' => 'Record deleted successfully']);
        }

        return response()->json(['message' => 'Error deleting the record'], 500);
    }


    public function updateIsCancelledStatus(Request $request, $id)
    {
        $receipt = Receipt::find($id);

        // Check the current value of is_done
        if ($receipt->is_cancelled == false) {
            $newIsCancelledValue = true; // If it's 0, set it to 1
        } else {
            $newIsCancelledValue = false; // If it's 1, set it to 0
        }

        $receipt->update(['is_cancelled' => $newIsCancelledValue]);
    }

    public static function getReceiptTotal($order_id) {

        $receipt = Receipt::where('order_id', $order_id)->firstOrFail();
        $order = $receipt->order;
        $deliveryService = $order->deliveryService;

        // Reduce 0.6 if payment type is COD and delivery service is HP
        $if_postal_delivery_reduction = 0;
        if ($receipt->order->payment_type_id == 2 && $deliveryService->delivery_company_id == 1) {
            $if_postal_delivery_reduction = 0.6;
        }

        $subtotal = OrderItemList::where('order_id', $receipt->order_id)->sum(\DB::raw('amount * price * ( ( 100 - discount ) / 100 )'));

        $deliveryCost = str_replace(',', '.', $deliveryService->default_cost);
        $deliveryCost = $deliveryCost - $if_postal_delivery_reduction;

        $total = number_format(($subtotal + $deliveryCost), 2, ',', '.');

        return $total;
    }

    public static function getTotalForAllReceipts($mode) {
        if ($mode === 1) {
            $receipts = Receipt::where('is_cancelled', 0)
                                ->get();
        } elseif ($mode === 2023) {
            $receipts = Receipt::where('is_cancelled', 0)
                                ->where('year', 2023)
                                ->get();
        } elseif ($mode === 2024) {
            $receipts = Receipt::where('is_cancelled', 0)
                                ->where('year', 2024)
                                ->get();
        } elseif ($mode === 3) {
            $receipts = Receipt::whereNotNull('paid_date')
                                ->get();
        } else {
            return;
        }
    
        $totalSum = 0;
        foreach ($receipts as $receipt) {
    
            $order = Order::where('id', $receipt->order_id)->firstOrFail();
            $deliveryService = DeliveryService::where('id', $order->delivery_service_id)->firstOrFail();
    
            // Calculating the total similar to the previous function
            $if_postal_delivery_reduction = 0;
            if ($receipt->order->payment_type_id == 2 && $deliveryService->company == "hp") {
                $if_postal_delivery_reduction = 0.6;
            }
    
            $subtotal = OrderItemList::where('order_id', $receipt->order_id)->sum(\DB::raw('amount * price * ( ( 100 - discount ) / 100 )'));

            $deliveryCost = str_replace(',', '.', $deliveryService->default_cost);
            $deliveryCost = $deliveryCost - $if_postal_delivery_reduction;
    
            $totalSum += ($subtotal + $deliveryCost);
        }
    
        return number_format(($totalSum), 2, ',', '.');
    }

    public static function countReceipts($year) {
        return Receipt::where('is_cancelled', 0)
                      ->where('year', $year)
                      ->count();
    }
}
