<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
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

    public function index($year = null)
    {
        if (is_null($year)) {
            $years = WorkYears::orderBy('year', 'desc')->first();
        } else {
            $years = Carbon::now()->year;
        }

        $receipts = Receipt::where('year', $year)->orderBy('number')->paginate(25);
        $orders = Order::whereNull('date_cancelled')->orderBy('id')->get();
        $latest = GlobalService::getLatestReceiptNumber($year);

        foreach ($receipts as $receipt) {
            $receipt->totalAmount = GlobalService::calculateReceiptTotal($receipt->order_id);
        }

        return view('receipts', [
            'receipts' => $receipts,
            'orders' => $orders,
            'latest' => $latest
        ]);
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
            return redirect()->back()->withInput()->with('error', "Račun s brojem <b>{$request->number}</b> već postoji u <b>{$request->year}.</b> godini.");
        }
    
        Receipt::create($request->only('number', 'order_id', 'year'));
    
        return redirect()->back()->with('success', "Račun broj <b>{$request->number$number}</b> uspješno je dodan u <b>{$request->number$year}.</b> godinu!");
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

}