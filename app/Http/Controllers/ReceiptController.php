<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Receipt;
use App\Models\Order;
use App\Models\DeliveryService;
use App\Models\OrderItemList;
use App\Models\WorkYears;
use App\Models\KprItemList;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Services\GlobalService;

class ReceiptController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($year = null)
    {
        if (is_null($year)) {
            $years = WorkYears::orderBy('year', 'desc')->first();
        } else {
            $years = Carbon::now()->year;
        }

        $orderIdsWithReceipts = Receipt::where('is_cancelled', 0)->pluck('order_id')->toArray(); // Get all order IDs that have receipts
        $orders = Order::whereNotIn('id', $orderIdsWithReceipts)->with('customer')->get(); // Get all orders that do not have receipts
        $receipts = Receipt::with('kprItem')->where('year', $year)->orderBy('number')->paginate(25);
        $latest = GlobalService::getLatestReceiptNumber($year);

        foreach ($receipts as $receipt) {
            $receipt->customerName = $receipt->order->customer->name ?? '';
            $receipt->paymentTypeName = $receipt->order->paymentType->name ?? '';
            $receipt->formatedDateCreatedAt = Carbon::parse($receipt->created_at)->format('d.m.Y - H:i:s');
            $receipt->totalAmount = GlobalService::calculateReceiptTotal($receipt->order_id); // Get total amount
            if (!is_null($receipt->cancelled_receipt_id)) { $receipt->totalAmount *= -1; } // Invert total amount if receipt is cancelling another receipt
            $receipt->totalAmount = number_format($receipt->totalAmount, 2, ','); // Format total amount for display
        }

        return view('pages.receipts.index', compact(
            'receipts', 'orders', 'latest'
        ));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'year' => 'required',
            'number' => 'required'
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', 'Molimo popunite sva polja.');
        }
    
        $exists = Receipt::where('year', $request->year)
                         ->where('number', $request->number)
                         ->exists();
    
        if ($exists) {
            return redirect()->back()->withInput()->with('error', "Račun s brojem {$request->number} već postoji u {$request->year}. godini.");
        }
    
        Receipt::create($request->only('number', 'order_id', 'year'));
    
        return redirect()->back()->with('success', "Račun broj {$request->number} uspješno je dodan u {$request->year}. godinu!");
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

    public function getLatestNumber($year)
    {
        $latest = GlobalService::getLatestReceiptNumber($year);

        return response()->json([
            'latest' => $latest
        ]);
    }

}